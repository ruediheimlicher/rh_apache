// File: LED_driver.h
// controls 16 LEDs
// connections:
//    A - B - C - D  => PA0 - PA1 - PA2 - PA3   active high
//    E - F - G - H  => PA5 - PA7 - PB0 - PB1   active low

#include <avr/io.h> 

#define setbit(port,bit) port |=  (1<<bit)
#define clrbit(port,bit) port &= ~(1<<bit)

void display16LED (unsigned char val);



