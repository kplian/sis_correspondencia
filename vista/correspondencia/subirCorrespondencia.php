<?php
/**
*@package pXP
*@file gen-Sensor.php
*@author  (rarteaga)
*@date 06-09-2011 11:45:42
*@description permites subir archivos  de imegenes a la tabla de personas
*/
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.subirCorrespondencia=Ext.extend(Phx.frmInterfaz,{

	constructor:function(config)
	{
		
		
    	//llama al constructor de la clase padre
		Phx.vista.subirCorrespondencia.superclass.constructor.call(this,config);
		this.init();	
		this.loadValoresIniciales()	
		
	},
	

	
	loadValoresIniciales:function()
	{
		
		Phx.vista.subirCorrespondencia.superclass.loadValoresIniciales.call(this);
		this.getComponente('id_correspondencia').setValue(this.id_correspondencia);
		this.argumentExtraSubmit.version = this.version;
		this.argumentExtraSubmit.id_gestion = this.id_gestion;
		this.argumentExtraSubmit.numero = this.numero;
		
				
	},
	
	
	successSave:function(resp){
        Phx.CP.loadingHide();
        Phx.CP.getPagina(this.idContenedorPadre).reload();
        this.panel.close();
    },
				
	
	Atributos:[
	    {
   	      config:{
			labelSeparator:'',
			inputType:'hidden',
			name: 'id_correspondencia'

		   },
		  type:'Field',
		  form:true 
		
	    },
		{
			//configuracion del componente
		   config:{
					fieldLabel: "Archivo",
					gwidth: 130,
					labelSeparator:'',
					inputType:'file',
					name: 'file_correspondencia',
					maxLength:150,
					anchor:'100%',
					validateValue:function(archivo){
						var extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase(); 
						//if(extension!='.pdf' && extension!='.PDF'){
						if(extension!='.pdf' && extension!='.PDF' && extension!='.docx' && extension!='.DOCX' && extension!='.doc' && extension!='.DOC' && extension!='.jpg' && extension!='.png'){
								this.markInvalid('solo se admiten archivos PDF, DOC, DOCX, JPG, PNG');
								return false
						}
						else{
							this.clearInvalid();
						    return true
						}
					}	
			},
			type:'Field',
		    form:true 
		}		
	],
	title:'Subir archivo',
	ActSave:'../../sis_correspondencia/control/Correspondencia/subirCorrespondencia',
	fileUpload:true,	
	}
)
	
</script>