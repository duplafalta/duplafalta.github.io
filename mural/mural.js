/* -----------------------------------------------------------------*/
/*                      Inter4u - Eldon Pinheiro                    */
/* -----------------------------------------------------------------*/

// Conta caracteres
function ContaCaracteres(){
   intCaracteres = 400 - document.mural.comentario.value.length;
   if (intCaracteres > 0){
      document.mural.caracteres.value = intCaracteres;
      return true;
   }
   else {
      document.mural.caracteres.value = 0;
      document.mural.comentario.value = document.mural.comentario.value.substr(0,400)
      return false;
   }
}

// Imprime os emoticons na tela
function emoticon(text) {
	var txtarea = document.mural.comentario;
	text = ' ' + text + ' ';
	if (txtarea.createTextRange && txtarea.caretPos) {
		var caretPos = txtarea.caretPos;
		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text + ' ' : text;
		txtarea.focus();
	}
	else {
		txtarea.value  += text;
		txtarea.focus();
	}
}
// Imprime os emoticons na tela
function emoticon2(text) {
	var txtarea = opener.document.mural.comentario;
	text = ' ' + text + ' ';
	if (txtarea.createTextRange && txtarea.caretPos) {
		var caretPos = txtarea.caretPos;
		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text + ' ' : text;
	}
	else {
		txtarea.value  += text;
	}
}

// Popup
function popup(url, intWi, intHei, scr, nome, intTop, intLeft) {
	if (intLeft == null){
		intLeft = 10
		intTop = 10
	}
	var janela = null
	janela=window.open(url,nome,'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars='+scr+',resizable=no,menubar=no,width=' + intWi + ',height=' + intHei+ ', left=' + intLeft + ', top=' + intTop + '');
}
// Função proteção contra flood
function flood() {
    alert ('Você já enviou uma mensagem, por favor tente mais tarde!!');
}

// Função proteção contra ip
function ip_bloq() {
    alert ('Seu IP foi bloqueado, por favor tente mais tarde!!');
}

//  Teclas de atalho
function atalho() {
     //F2
	 if (event.keyCode==113) {
        location='admin/'
        event.keyCode=0
        return false
     }
}
document.onkeydown=atalho
