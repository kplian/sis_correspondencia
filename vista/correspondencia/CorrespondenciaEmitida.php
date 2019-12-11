<?php
/**
 * @package pXP
 * @file gen-SistemaDist.php
 * @author  (fprudencio)
 * @date 20-09-2011 10:22:05
 * @description Archivo con la interfaz de usuario que permite
 *dar el visto a solicitudes de compra
 *
 */
#HISTORIAL DE MODIFICACIONES:
#ISSUE          FECHA        AUTOR        DESCRIPCION
#4      		25/07/2019   MCGH         Adición del campo persona_remitente, fecha recepción,
#										  Eliminación del campo id_clasificador,
#										  Adición del campo persona_destino

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.CorrespondenciaEmitida = {
        bsave: false,

        require: '../../../sis_correspondencia/vista/correspondencia/Correspondencia.php',
        requireclase: 'Phx.vista.Correspondencia',
        title: 'Emitidas',
        nombreVista: 'CorrespondenciaEmitida',
		fwidth: '35%',

        constructor: function (config) {        	
			this.Atributos[this.getIndAtributo('id_funcionario_saliente')].grid=false;
			this.Atributos[this.getIndAtributo('id_institucion_destino')].grid=false;
			this.Atributos[this.getIndAtributo('id_persona_destino')].grid=false;
			this.Atributos[this.getIndAtributo('id_funcionarios')].grid=false;
			this.Atributos[this.getIndAtributo('asociar')].grid=false;
			this.Atributos[this.getIndAtributo('observaciones_archivado')].grid=false;
			this.Atributos[this.getIndAtributo('cite')].grid=false;
			this.Atributos[this.getIndAtributo('id_institucion_remitente')].grid=false;
			this.Atributos[this.getIndAtributo('id_persona_remitente')].grid=false;
			this.Atributos[this.getIndAtributo('otros_adjuntos')].grid=false;
			this.Atributos[this.getIndAtributo('nro_paginas')].grid=false;
			this.Atributos[this.getIndAtributo('id_funcionario_destino')].grid=false;
			this.Atributos[this.getIndAtributo('fecha_ult_derivado')].grid=false;
			//  this.Atributos[this.getIndAtributo('persona_firma')].grid=false;
			//this.Atributos[this.getIndAtributo('tipo_documento')].grid=false;  
			        
			Phx.vista.CorrespondenciaEmitida.superclass.constructor.call(this, config);   
			
			this.getBoton('FinalizarExterna').hide();
			this.getBoton('ImpCodigo').hide();
			this.getBoton('ImpCodigoDoc').hide();
			this.getBoton('HojaRuta').hide();
			this.getBoton('Finalizar').hide();
			this.getBoton('Habilitar').hide();
			this.init();
			this.argumentExtraSubmit={'vista':'CorrespondenciaInterna'};
			this.store.baseParams = {'interface': 'interna'};
			this.load({params: {start: 0, limit: 50}})
        },
      
      	onButtonNew: function () {            
        	Phx.vista.Correspondencia.superclass.onButtonNew.call(this);
            this.cmpFechaDoc = this.getComponente('fecha_documento');
            this.Cmp.id_funcionario.store.baseParams.fecha = new Date().dateFormat(this.cmpFechaDoc.format);
            this.Cmp.id_funcionario.store.load({params:{start:0,limit:this.tam_pag},
                callback : function (r) {
                    if (r.length == 1 ) {
                        this.Cmp.id_funcionario.setValue(r[0].data.id_funcionario);
                    }
                }, scope : this

            });

            var cmbDoc = this.getComponente('id_documento');
            var cmpFuncionarios = this.getComponente('id_funcionarios');
            this.adminGrupo({ocultar:[4],mostrar: [0, 1, 2, 3]});
            this.mostrarComponente(cmpFuncionarios);
            this.ocultarComponente(this.Cmp.id_persona_destino);
            this.ocultarComponente(this.Cmp.id_persona_remitente);
            this.ocultarComponente(this.Cmp.id_institucion_remitente);
            this.ocultarComponente(this.Cmp.id_institucion_destino);
            this.ocultarComponente(this.Cmp.fecha_creacion_documento);
            this.ocultarComponente(this.Cmp.cite);
            this.ocultarComponente(this.Cmp.otros_adjuntos);
            this.ocultarComponente(this.Cmp.nro_paginas);
         	//this.ocultarComponente(this.Cmp.persona_firma);
            //this.ocultarComponente(this.Cmp.tipo_documento);
            this.ocultarComponente(this.Cmp.persona_remitente); //#4
            this.ocultarComponente(this.Cmp.persona_destino); //#4
            //this.getComponente('id_clasificador').enable();
            this.getComponente('fecha_documento').disable();
            this.getComponente('mensaje').enable();
            this.getComponente('nivel_prioridad').enable();
            this.getComponente('referencia').enable();

            this.tipo = this.getComponente('tipo');
            this.tipo.setValue('interna');
		    this.tipo.disable(true);
          
            cmbDoc.store.baseParams.tipo = 'interna';//valor por dfecto es interna
            cmbDoc.modificado = true;
            cmbDoc.reset();
        },

        onButtonEdit: function () {
            Phx.vista.Correspondencia.superclass.onButtonEdit.call(this);
            this.adminGrupo({mostrar: [2], ocultar: [0, 1, 3]});
            this.ocultarComponente(this.Cmp.cite);
            this.ocultarComponente(this.Cmp.otros_adjuntos);
            this.ocultarComponente(this.Cmp.nro_paginas);
            this.ocultarComponente(this.Cmp.tipo_documento);          
            var data = this.sm.getSelected().data;
            console.log(data, data.estado)
            if (data.estado == 'borrador_envio') {
                this.getComponente('id_clasificador').enable();
                this.getComponente('mensaje').enable();
                this.getComponente('nivel_prioridad').enable();
                this.getComponente('referencia').enable();
            }
            else if (data.estado == 'enviado') {
                this.getComponente('id_clasificador').disable();
                this.getComponente('mensaje').disable();
                this.getComponente('nivel_prioridad').disable();
                this.getComponente('referencia').disable();
            }
        },
     
        preparaMenu: function (n) {
            Phx.vista.Correspondencia.superclass.preparaMenu.call(this, n);
            var data = this.getSelectedData();
            var tb = this.tbar;
            //si el archivo esta escaneado se permite visualizar             
            if (data['version'] > 0) {
                this.getBoton('VerDocumento').enable();
                this.getBoton('Derivar').enable()
            }
            else {
                this.getBoton('VerDocumento').disable();
                this.getBoton('Derivar').disable()
            }
       		//cuando el conrtato esta registrado el abogado no puede hacerle mas cambios
            if (data['estado'] == 'borrador_envio') {
                this.getBoton('SubirDocumento').enable();
                this.getBoton('Plantilla').enable();
                this.getBoton('Adjuntos').enable();
                this.getBoton('Corregir').disable();
                //this.getBoton('Historico').disable();
                this.getBoton('Archivar').disable();
            }
            if (data['estado'] == 'enviado') {
				if (tb) {
                //this.getBoton('Plantilla').disable();
this.getBoton('Adjuntos').enable();
this.getBoton('SubirDocumento').enable();
this.getBoton('Corregir').enable();
this.getBoton('Derivar').disable();
this.getBoton('Historico').enable();
 this.getBoton('Archivar').enable();
this.getBoton('edit').disable();
this.getBoton('del').disable();
                    
                }

            }


            return tb

        },
        
        enableDisable:function(val){
        	
      if(val =='interna'){
            this.cmpResponde.store.baseParams.tipo = val;
            this.cmpResponde.modificado = true;
            this.cmpResponde.enable();
            this.cmpResponde.reset()
         }
        else if (val =='externa'){
            this.cmpResponde.store.baseParams.tipo = val;
            this.cmpResponde.modificado = true;
            this.cmpResponde.enable();
            this.cmpResponde.reset();
            
         }
         else {
         	   this.cmpResponde.disable(); 
         	    this.cmpResponde.reset();     	
         }
         
     },
		  iniciarEventos: function () {

		  	this.getBoton('Habilitar').hide();
	         this.cmpResponde = this.getComponente('id_correspondencias_asociadas');
             this.cmpAsocia = this.getComponente('asociar');
 
            this.cmpAsocia.on('change', function (groupRadio,radio) {
            	if(radio.inputValue){this.enableDisable(radio.inputValue);}
            },this);
            

        }
        

    };
</script>
