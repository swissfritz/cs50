/****************************************************************************
 * dictionary.c
 *
 * Computer Science 50
 * Problem Set 5
 *
 * Implements a dictionary's functionality.
 ***************************************************************************/

#include <stdbool.h>
#include <stdio.h>
#include <ctype.h>
#include <stdlib.h>
#include <string.h>
#include <math.h>

#include "dictionary.h"

// Define size of hashtable
#define SIZE 5000

// Create nodes
typedef struct node
{
    char word[LENGTH+1];
    struct node* next;
}
node;

// Create hashtable
node* hashtable[SIZE] = {NULL};

// Hash function (djb2 modified)
int hash (const char* word)
{
    int hash = 5381;
    int c = 0;
    for (int i = 0; word[i] != '\0'; i++)
    {
        // Attribute values 1 to 26 ...
        if(isalpha(word[i]))
            c = word [i] - 'a' + 1;
        
        // ... and 27
        else
            c = 27;
            
        hash = ((hash << 5) + hash + c) % SIZE;
    }
    return hash;    
}

// Store dictionary size
int dict_size = 0;

/**
 * Returns true if word is in dictionary else false.
 */
bool check(const char* word)
{
    // Make sure hashtable exists
    if (hashtable == NULL)
    {
        printf("No dictionary present");
        return false;
    }

    // Store the word to check in lower case letters
    char to_check[LENGTH + 1];
    int n = strlen(word);
    for (int i = 0; i < n; i++)
    {
        to_check[i] = tolower(word[i]);     
    }
    to_check[n] = '\0';
    
    // Find bucket
    int index = hash(to_check) % SIZE;
    
    // If bucket not empty at index, traverse
    node* cursor = hashtable[index];
    while (cursor != NULL)
    {
        if (strcmp(to_check, cursor->word) == 0)
        {
            return true;
        }
        cursor = cursor->next;
    }
    
    // word not found ?
    return false;
}

/**
 * Loads dictionary into memory.  Returns true if successful else false.
 */
bool load(const char* dictionary)
{
    // Open dictionary
    FILE* file = fopen(dictionary, "r");
    if (file == NULL)
        return false;
    
    // Array for the current word
    char word[LENGTH+1];
    
    // Iterate through the words in the dictionary
    while (fscanf(file, "%s\n", word)!= EOF)
    {
        // Make sure a word has been read from dictionary
        if (word == NULL)
        {
            printf("No word has been read from dictionary");
            return false;
        }
        
        // Increment dictionary size for later use in "size"
        dict_size++;
        
        // Create a new node
        node* newWord = malloc(sizeof(node));
        
        // Assign the value of word
        strcpy(newWord->word, word);
        
        // Assign a bucket
        int index = hash(word);
        
        // If bucket is empty: set first node
        if (hashtable[index] == NULL)
        {
            hashtable[index] = newWord;
            newWord->next = NULL;
        }
        
        // Else, append node
        else
        {
            newWord->next = hashtable[index];
            hashtable[index] = newWord;
        }      
    }
    
    // Close dictionary file
    fclose(file);
     
    return true;
}


/**
 * Returns number of words in dictionary if loaded else 0 if not yet loaded.
 */
unsigned int size(void)
{
    if (dict_size > 0)
    {
        return dict_size;
    }
     
    return 0;
}

/**
 * Unloads dictionary from memory.  Returns true if successful else false.
 */
bool unload(void)
{
    // Make sure hashtable exists
    if (hashtable == NULL)
    {
        return false;
    }
    
    // Iterate through hashtable freeing all buckets
    int index = 0;
    while (index < SIZE)
    {
        if (hashtable[index] == NULL)
        {
            index++;
        }
        
        else
        {
            while(hashtable[index] != NULL)
            {
                node* cursor = hashtable[index];
                hashtable[index] = cursor->next;
                free(cursor);
            }

            index++;
        }
    }

    return true;
}
