function monatskalender(wahljahr,wahlmonat)
{
;
}

function indexschreiben(index)
{
	//alert("Der Index ist	"+index);
	//document.kalenderindex.hallofeld.value = index;
	document.kalenderindex.wahltag.value = index;
	document.forms["kalenderform"].submit();
	
}

function klickdatum ()
{
	return document.kalenderindex.hallofeld.value;
}

function halloschreiben()
{

alert("Hallo");
	//document.kalenderindex.hallofeld.focus();
	//document.kalenderindex.hallofeld.value = "Hallo";
}

function datumfehler(postjahr, heutejahr)
{
if (postjahr > heutejahr)
{
alert("Datumfehler postjahr:"+postjahr +"heutejahr: "+heutejahr );
}
else
{
alert("Datum OK");
}
}