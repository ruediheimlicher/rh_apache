// File: LED_driver.c
// controls 16 LEDs; switches on LED #val
// connections:
//    A - B - C - D  => PA0 - PA1 - PA2 - PA3   active high
//    E - F - G - H  => PA5 - PA7 - PB0 - PB1   active low

#include "LED_driver.h"

void display16LED (unsigned char val)
{
	clrbit (PORTA, PA0);	// A
	clrbit (PORTA, PA1);	// B
	clrbit (PORTA, PA2);	// C
	clrbit (PORTA, PA3);	// D
	switch (val & 3) {
		case 0:	setbit (PORTA, PA0);	// A
			break;
		case 1:	setbit (PORTA, PA1);	// B
			break;
		case 2:	setbit (PORTA, PA2);	// C
			break;
		case 3:	setbit (PORTA, PA3);	// D
			break;
		default:	break;
	}
	setbit (PORTA, PA5);	// E
	setbit (PORTA, PA7);	// F
	setbit (PORTB, PB0);	// G
	setbit (PORTB, PB1);	// H

	switch ((val >> 2) & 3) {
		case 0:	clrbit (PORTA, PA5);	// E
			break;
		case 1:	clrbit (PORTA, PA7);	// F
			break;
		case 2:	clrbit (PORTB, PB0);	// G
			break;
		case 3:	clrbit (PORTB, PB1);	// H
			break;
		default:	break;
	}
}

