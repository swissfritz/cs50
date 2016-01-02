/**
* resize.c
*
* Computer Science 50
* Problem Set 4
* 
* Multiplies width and height of a BMP by a factor of n
*/
     
#include <stdio.h>
#include <stdlib.h>

#include "bmp.h"

int main(int argc, char* argv[])
{
    // ensure proper usage
    if (argc != 4)
    {
        printf("Usage: n infile outfile\n");
        return 1;
    }
    
    // resize factor
    int n = atoi(argv[1]);
    
    if (n <= 0 || n > 100)
    {
        printf("n must be between 1 and 100");
        return 2;
    }

    // filenames
    char* infile = argv[2];
    char* outfile = argv[3];

    // open input file 
    FILE* inptr = fopen(infile, "r");
    if (inptr == NULL)
    {
        printf("Could not open %s.\n", infile);
        return 3;
    }

    // open output file
    FILE* outptr = fopen(outfile, "w");
    if (outptr == NULL)
    {
        fclose(inptr);
        fprintf(stderr, "Could not create %s.\n", outfile);
        return 4;
    }

    // read infile's BITMAPFILEHEADER
    BITMAPFILEHEADER bf;
    fread(&bf, sizeof(BITMAPFILEHEADER), 1, inptr);

    // read infile's BITMAPINFOHEADER
    BITMAPINFOHEADER bi;
    fread(&bi, sizeof(BITMAPINFOHEADER), 1, inptr);

    // ensure infile is (likely) a 24-bit uncompressed BMP 4.0
    if (bf.bfType != 0x4d42 || bf.bfOffBits != 54 || bi.biSize != 40 || 
        bi.biBitCount != 24 || bi.biCompression != 0)
    {
        fclose(outptr);
        fclose(inptr);
        fprintf(stderr, "Unsupported file format.\n");
        return 5;
    }

    // remember old dimensions
    int inWidth = bi.biWidth;
    int inHeight = bi.biHeight;
    int absHeight = abs(bi.biHeight);
    
    // attribute new dimensions
    bi.biWidth = inWidth * n;
    bi.biHeight = inHeight * n;
    
    // padding for scanlines
    int in_padding = (4 - (inWidth * sizeof(RGBTRIPLE)) % 4) % 4;
    int out_padding =  (4 - (bi.biWidth * sizeof(RGBTRIPLE)) % 4) % 4;

    // new image size
    bi.biSizeImage = (bi.biWidth * sizeof(RGBTRIPLE) + out_padding) * abs(bi.biHeight);
    bf.bfSize = 54 + bi.biSizeImage; 
    
    // write outfile's BITMAPFILEHEADER
    fwrite(&bf, sizeof(BITMAPFILEHEADER), 1, outptr);
    
    // write outfile's BITMAPINFOHEADER
    fwrite(&bi, sizeof(BITMAPINFOHEADER), 1, outptr);
        
    // read image and resize by n
    // read source's scanlines
    for (int i = 0; i < absHeight; i++)
    {
        // write n output lines
        for (int l = 0; l < n; l++)
        {   
            // go to the beginning of the appropriate scanline
            fseek(inptr, (54 + ((inWidth * 3 + in_padding) * i)), SEEK_SET);
            
            // read RGB-triples from scanline 
            for (int j = 0; j < inWidth; j++)
            {
                // temporary storage
                RGBTRIPLE triple;

                // read RGB triple from infile
                fread(&triple, sizeof(RGBTRIPLE), 1, inptr);

                // write RGB triple n times to outfile
                for (int k = 0; k < n; k++)
                    fwrite(&triple, sizeof(RGBTRIPLE), 1, outptr);                   
            }
            
            // add padding to output line
            for (int m = 0; m < out_padding; m++)
                fputc(0x00, outptr);
        }
    }

    // close infile
    fclose(inptr);

    // close outfile
    fclose(outptr);

    // that's all folks
    return 0;
}

