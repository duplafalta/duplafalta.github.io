// JavaScript Document
var snowsrc=window.parent.endereco
 
//how many snowflakes there will be (currently 12)
var no = 40;
 
//How fast will the snow disappear (0 is never)
var hidesnowtime = 0;
 
//The height the snow will reach before it disappears ("windowheight" or "pageheight")
var snowdistance = "pageheight";
 
///////////////////////////////End of Settings///////////////////////////////////
var ie4up = (document.all) ? 1 : 0;
var ns6up = (document.getElementById&&!document.all) ? 1 : 0;
 
function iecompattest(){
  return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}
 
var dx, xp, yp;
var am, stx, sty;
var i, doc_width = 800, doc_height = 600;
var parVeri = 0;
 
if (ns6up){
  doc_width = self.innerWidth;
  doc_height = self.innerHeight;
}else if (ie4up){
  doc_width = document.body.clientWidth;
  doc_height = document.body.clientHeight;
}
 
dx = new Array();
xp = new Array();
yp = new Array();
am = new Array();
stx = new Array();
sty = new Array();
 
for (i = 0; i < no; ++ i){
  dx[i] = 0;
  xp[i] = Math.random()*(doc_width-50);
  yp[i] = Math.random()*doc_height;
  am[i] = Math.random()*20;
  stx[i] = 0.02 + Math.random()/10;
  sty[i] = 0.7 + Math.random();
  if (ie4up||ns6up){
      if (i == 0){
        document.write("<a href=\"http://www.darcweb.com.br\" target=\"_blank\"><div id=\"dot"+ i +"\" style=\"position: absolute; z-index: "+ i +"; visibility: visible; top: 0px; left: 15px;\"><img src='"+snowsrc+"' border=\"0\"><\/div><\/a>");
      }else{
        document.write("<div id=\"dot"+ i +"\" style=\"position: absolute; z-index: "+ i +"; visibility: visible; top: -80px; left: 15px;\"><img src='"+snowsrc+"' border=\"0\"><\/div>");
      }
  }
}
 
function snowIE_NS6(){
  doc_width = ns6up?window.innerWidth-20 : iecompattest().clientWidth-20;  doc_height=(window.innerHeight && snowdistance=="windowheight")? window.innerHeight : (ie4up && snowdistance=="windowheight")?  iecompattest().clientHeight : (ie4up && !window.opera && snowdistance=="pageheight")? iecompattest().scrollHeight : iecompattest().offsetHeight-300;
 
  for (i = 0; i < no; ++ i){
	  parVeri++;
      yp[i] += sty[i];
      if (yp[i] > doc_height-50){
        xp[i] = Math.random()*(doc_width-am[i]-30);
        yp[i] = 0;
        stx[i] = 0.02 + Math.random()/10;
        sty[i] = 0.7 + Math.random();
      }
 
      dx[i] += stx[i];
	  if(parVeri == 2){
		  document.getElementById("dot"+i).style.top=Number(yp[i])+"px";
		  document.getElementById("dot"+i).style.left=xp[i] + am[i]*Math.sin(dx[i])+"px";
		  //alert(parVeri);
		  parVeri = 0;
	  }else{
		  document.getElementById("dot"+i).style.top=320+Number(yp[i])+"px";
		  document.getElementById("dot"+i).style.left=xp[i] + am[i]*Math.sin(dx[i])+"px";
	  }
		 // alert(parVeri);
  }
  snowtimer=setTimeout("snowIE_NS6()", 30);
}
 
function hidesnow(){
  if(window.snowtimer){
  	  clearTimeout(snowtimer);
      for (i=0; i<no; i++){
		 document.getElementById("dot"+i).style.visibility="hidden"
	  }
  }
}
 
if (ie4up||ns6up){
  snowIE_NS6();
  if (hidesnowtime>0){
     setTimeout("hidesnow()", hidesnowtime*100);
  }
}