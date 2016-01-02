/**
 * recover.c
 *
 * Computer Science 50
 * Problem Set 4
 *
 * Recovers JPEGs from a forensic image.
 */

#include <stdio.h>
#include <stdlib.h>
#include <stdint.h>

int main(int argc, char* argv[])
{
    // Define variables.
    uint8_t buff[512];
    int counter = 0;
    char imgname[8];
    
    // Open source file
    FILE* inputr = fopen("card.raw", "r");
    
    // Check if exists
    if (inputr == NULL)
    {
        printf("Unable to read from \"card.raw\"\n");
        return 1;
    }
    
    // Declare outfile
    FILE* outputr = NULL;
    
    // Read from infile until EOF
    while(fread(&buff, 512, 1, inputr) != 0)
    {
        // Buffer contains beginning of a JPEG? Check first 4 bytes
        if (buff[0] == 0xff && buff[1] == 0xd8 && buff[2] == 0xff && (buff[3] == 0xe0 || buff[3] == 0xe1))
        {
            // Close last outfile if there is one open
            if (outputr != NULL)
            {
                fclose(outputr);
            } 
                       
            // Open next PEG
            sprintf(imgname, "%03d.jpg", counter);
            counter++;
            outputr = fopen(imgname, "w");
            
            // Check if created
            if (outputr == NULL)
            {
               printf("Unable to create outfile\n");
               return 2;
            }   
                   
            // Write first block into outfile
            fwrite(&buff, 512, 1, outputr);
        }       
        // Continue filling outfile until beginning of next JPEG
        else
        {
            if (outputr != NULL)
            {
                fwrite(&buff, 512, 1, outputr);
            }
        }
    }
    
    fclose(inputr);
    fclose(outputr);
    return 0;
}
