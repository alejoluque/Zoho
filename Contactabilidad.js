ver_lead = zoho.crm.getRecordById("Leads",id);
festivos = invokeurl
[
	url :"https://www.zohoapis.com/crm/v4/settings/holidays"
	type :GET
	connection:"crm"
];
// info festivos;
json_festivos = festivos.getJSON("holidays");
mapa_festivos = Collection();
for each  festivo in json_festivos
{
	fecha = festivo.get("date");
	// 	fecha = fecha.tostring("dd-MMM-yyyy");
	// // 	fecha = toDate(fecha, "dd-MMM-yyyy"); 
	// 	fecha = "'"+fecha+"'";
	mapa_festivos.insert(fecha);
	// 	info fecha;
}
info mapa_festivos;
// info ver_lead;
tiempo = "";
hora_creacion = ver_lead.get("Created_Time");
hora_creacion = replaceAll(hora_creacion,"T"," ");
hora_creacion = replaceAll(hora_creacion,"-05:00","");
info hora_creacion;
hora_gestion = ver_lead.get("Fecha_hora_inicio_de_gesti_n");
hora_gestion = replaceAll(hora_gestion,"T"," ");
hora_gestion = replaceAll(hora_gestion,"-05:00","");
info hora_gestion;
horas = hoursBetween(hora_creacion,hora_gestion);
minutos = hora_creacion.timeBetween(hora_gestion);
info "-----Horas----:" + horas;
info tiempo;
diastrabajo = hora_creacion.workDaysBetween(hora_gestion,{"Sunday"},mapa_festivos);
info "---------dias de trabajo---:" + diastrabajo;
info "-----------Horas laborales--------:";
horas_t = diastrabajo * 13;
info horas_t;
hora_trabajo_fin = hora_gestion.toString("YYYY-MM-dd 21:00:00");
info "Fecha gestion solo :" + hora_trabajo_fin;
hora_total_dia = hoursBetween(hora_gestion,hora_trabajo_fin);
info "hora diferencia mismo dia gestion: " + hora_total_dia;
hora_final = horas_t - hora_total_dia;
info "HORA FINAL::" + hora_final;
if(diastrabajo == 0)
{
	hora_final = horas;
}
else
{
	// 	hora_final = hora_final + 24;
	// 	hora_final = hora_final - 11;
	// 	info "Hora total restas:: " + hora_final;
}
if(horas == 0)
{
	info "horas es 0";
	tiempo = "00:" + minutos;
}
else
{
	tiempo = minutos;
	tiempo = replaceAll(tiempo,horas,hora_final);
}
info "TIEMPO CON HORAS Y MINUTOS:" + tiempo;
// tiempo = tiempo.toString("00:00:00");
info "TOTAL HORAS DEMORA GESTION: " + hora_final;
if(hora_final.contains("-"))
{
	info "Contiene negativo";
	if(diastrabajo == 0)
	{
		hora_final = horas;
	}
	else
	{
		hora_final = hora_final + 24;
		hora_final = hora_final - 11;
		info "Hora total restas:: " + hora_final;
	}
}
else
{
	info "No contiene  negativo";
}
actualiza_lead = Map();
actualiza_lead.put("Tiempo_de_gesti_n_hora_y_minutos",tiempo);
actualiza_lead.put("Tiempo_Total_de_gesti_n",hora_final.toString());
actualiza = zoho.crm.updateRecord("Leads",id,actualiza_lead);
info actualiza;
// info "-----------Horas - Horas laborales ---------";
// horas_c = horas - horas_t;
// dias = daysBetween(hora_creacion,hora_gestion);
// info "-----------Dias---------";
// info dias;
// info "-----------Dias No laborales---------";
// diasNoLaborales = dias - diastrabajo;
// info diasNoLaborales;
// info "-----------Horas No laborales---------";
// minutosNolaborales = diasNoLaborales * 24;
// info minutosNolaborales;
