/*****************************************************************************
* Project			: tiny_compass
* Author			: Wilfried Waetzig
* Date				:  30.09.2011
* File              : tiny_compass.c
* Compiler          : avr-gcc (WinAVR 20080610) 4.3.0
*
* Devices used 		: ATtiny84	master controller
* 					: HDMM01: 2-axis magnetic-field sensor
* With the AppNote	: AVR310 - Using the USI module as a TWI-Master
*
* Description       : The microcontoller samples every second
*					  the magnetic field x-axis/y-axis 
*					  from the module HDMM01
* Display with 16 LEDs arranged in a circle
*
****************************************************************************/

// Pin-connections of the ATiny84:
//	PA0 (13) - LED-A
//	PA1 (12) - LED-B
//	PA2 (11) - LED-C
//	PA3 (10) - LED-D
//	PA4  (9) - SCL for TWI	- SCK  for prog.
//	PA5  (8) - LED-E		- MISO for prog.
//	PA6  (7) - SDA for TWI	- MOSI for prog.
//	PA7  (6) - LED-F
//	PB0  (2) - LED-G
//	PB1  (3) - LED-H
//	PB2  (5) - 
//	PB4  (2) - RESET		- RES  for prog.
//	Vcc  (1)
//	GND (14)

#include <stdio.h>
#include <string.h> 
#include <stdint.h> 
#include <stdlib.h> 
#include <inttypes.h>
#include <avr/io.h> 
#include <avr/interrupt.h> 
#include <util/delay.h> 

#include "USI_TWI_Master.h"
#include "LED_driver.h"

//**************************************************************************

#define ADR_HDMM01		0x60 // HDMM01 address
#define REG_CONTROL		0x00
#define FUNC_RESET		0x04
#define FUNC_SET		0x02
#define FUNC_TM			0x01
#define TWI_WRITE		0x00
#define TWI_READ		0x01
#define VAL_OFFSET		2048

#define SWITCH_1	(PINB & (1<<PB2))

//**************************************************************************


typedef union _WORD {
    unsigned int _word;
    struct {
        unsigned char byte0;
        unsigned char byte1;
    };
} WORD;

// ++++ global variables
unsigned int meas_x_axis, meas_y_axis;
unsigned int mavr_x_axis, mavr_y_axis;
int msgn_x_axis, msgn_y_axis, meas_angle;
unsigned char messageBuf[7];
WORD saveWORD;


//**************************************************************************
// start function on HDMM01 (RESET/SET/TM)
unsigned char start_func (unsigned char num) 
{
	unsigned char temp;
	messageBuf[0] = ADR_HDMM01|TWI_WRITE;
	messageBuf[1] = REG_CONTROL;
	messageBuf[2] = num;
	temp = USI_TWI_Start_Transceiver_With_Data( messageBuf, 3 );
	temp = USI_TWI_Master_Stop();
	_delay_ms(10);
	return temp;
}

// get value from x/y-axis
unsigned char get_values (void) 
{  
	unsigned char temp;
	temp = start_func (FUNC_TM);
	messageBuf[0] = ADR_HDMM01|TWI_WRITE;
	messageBuf[1] = REG_CONTROL;
	temp = USI_TWI_Start_Transceiver_With_Data( messageBuf, 2 );
	messageBuf[0] = ADR_HDMM01|TWI_READ;	// read ADC-value
	temp = USI_TWI_Start_Transceiver_With_Data( messageBuf, 6 );
	temp = USI_TWI_Master_Stop();
	// store values
	saveWORD.byte1 = messageBuf[2];	// MSB
	saveWORD.byte0 = messageBuf[3];	// LSB
	meas_x_axis = saveWORD._word;
	saveWORD.byte1 = messageBuf[4];	// MSB
	saveWORD.byte0 = messageBuf[5];	// LSB
	meas_y_axis = saveWORD._word;
	return temp;
}

//**************************************************************************

// calculate the angle 0..360 degrees from measured values (xm, ym)
int calc_angle (int xm, int ym)
{
	int xabs, yabs, zval, zdeg;
	unsigned char sxm, sym, sxy;
// get the quadrant from the signs of x_meas / y_meas	
	sxm = (xm < 0)? 1:0;
	sym = (ym < 0)? 2:0;
// value for arctan
	xabs = abs(xm);
	yabs = abs(ym);
	sxy = (yabs > xabs)? 1:0;
	if (sxy != 0)
		zval = (xabs << 7) / yabs;
	else
		zval = (yabs << 7) / xabs;
	// approximation for arctan(z),
	// Handbook of Mathematical Funcions page 81, formula 4.4.48
	// arctan(x) = x / (1.0 + 0.28*x*x)    error < 5*10^-3 for 0 < x < 1 .
	// with: z = (xm*128)/ym, integer-calculation
	// correction to degrees with 180/PI = 57.296 ; 7334 = 180/PI*128 .
	// return with degrees 0 .. 45
	zdeg = ((long)zval * 7334) / ((long)zval * (long)zval * 28 / 100 + 16384);
	if (sxy != 0) 
		zdeg = 90 - zdeg;	
	// correction for quadrant
	switch (sxm + sym) {
		case 0: return (zdeg);
		case 1: return (180-zdeg);
		case 2: return (360-zdeg);
		case 3: return (180+zdeg);
		default: return (0);
	};
}	

//**************************************************************************

int main( void )
{
	unsigned char temp, mloop, mcalib;
	// initialise LED port and USI_TWI pins
	DDRA  = 0xFF;	// all output
	PORTA = 0;
	//Switch port 
	DDRB  = 0x03;	// PB0,PB1 output 
	PORTB = (1<<PB2);	// pull-up for switch
	// initialise the LEDs, show circulating point
	for (temp=0; temp<32; temp++) {
		display16LED(temp);
		_delay_ms(100);
	}
	// initalise USI_TWI_Master
	USI_TWI_Master_Initialise();
	mcalib = 0;

	// ====== loop ================================================
	for(;;) {
		// read measurements - recalibrate every 128 measurements
		mcalib++;
		if (mcalib == 128) {
			mcalib = 0;
			temp = start_func (FUNC_RESET);
			temp = start_func (FUNC_SET);
		}
		// get average of 8 values (meas_x_axis, meas_y_axis)
		mavr_x_axis = 0;
		mavr_y_axis = 0;
		for (mloop=0; mloop<8; mloop++) {
			temp = get_values();
			mavr_x_axis += meas_x_axis;
			mavr_y_axis += meas_y_axis;
			_delay_ms(25);	
		}
		// get average values and correct for offset
		msgn_x_axis = (mavr_x_axis >> 3) - VAL_OFFSET;
		msgn_y_axis = (mavr_y_axis >> 3) - VAL_OFFSET;
		// calculate the angle 0..360 degrees
		meas_angle = calc_angle (msgn_x_axis, msgn_y_axis);
		// LEDs
			temp = meas_angle * 16 / 360;
			temp = 16 - (temp & 15);
			display16LED(temp);
	}	// end for(;;)
}
