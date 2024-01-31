
opciones = ZDK.Client.getInput([{ type: 'text', label: 'Teléfono' }, { type: 'text', label: 'Correo' }], 'Verifica duplicados', 'OK', 'Cancel');


telefono = opciones[0];
email = opciones[1];

log(telefono);
log(email);


var response_tel = ZDK.Apps.CRM.Functions.execute("clientscriptVerificaDuplicado", { "telefono": telefono });


var response_correo = ZDK.Apps.CRM.Functions.execute("clientscriptVerificaDuplicado", {"correo":email});
const respuesta_correo = response_correo.details.output;
const respuesta_tel = response_tel.details.output;
log(respuesta_tel);

if (respuesta_tel == "si") {

    ZDK.Client.showAlert('Ya existe un Lead con el mismo teléfono');
    var save_btn = ZDK.Page.getButton('record_save');
    var save_btn_new = ZDK.Page.getButton('record_save_and_new');
    save_btn.disable();
    save_btn_new.disable();
    var telefono_con = ZDK.Page.getField('Phone');
    telefono_con.setValue(telefono);
    telefono_con.showError('Teléfono duplicado');
    
    
} else {

   var save_btn = ZDK.Page.getButton('record_save');
    var save_btn_new = ZDK.Page.getButton('record_save_and_new');
    save_btn.enable();
    save_btn_new.enable();
    var telefono_con = ZDK.Page.getField('Phone');
    telefono_con.setValue(telefono);

}

if (respuesta_correo == "si") {

    ZDK.Client.showAlert('Ya existe un Lead con el mismo Correo');
    var save_btn = ZDK.Page.getButton('record_save');
    var save_btn_new = ZDK.Page.getButton('record_save_and_new');
    save_btn.disable();
    save_btn_new.disable();
    var correo_con = ZDK.Page.getField('Email');
    correo_con.setValue(email);
    correo_con.showError('Correo duplicado');
    
    
} else {

   var save_btn = ZDK.Page.getButton('record_save');
    var save_btn_new = ZDK.Page.getButton('record_save_and_new');
    save_btn.enable();
    save_btn_new.enable();
     var correo_con = ZDK.Page.getField('Email');
    correo_con.setValue(email);
    

}
