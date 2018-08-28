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
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.CorrespondenciaEmitida = {
        bsave: false,

        require: '../../../sis_correspondencia/vista/correspondencia/Correspondencia.php',
        requireclase: 'Phx.vista.Correspondencia',
        title: 'Emitidas',
        nombreVista: 'CorrespondenciaEmitida',


        constructor: function (config) {
        	
        	this.Atributos[this.getIndAtributo('fecha_reg')].grid=true;
            this.Atributos[this.getIndAtributo('fecha_creacion_documento')].form=false;
            Phx.vista.CorrespondenciaEmitida.superclass.constructor.call(this, config);   
         
            this.init();
            this.store.baseParams = {'interface': 'interna'};
            this.load({params: {start: 0, limit: 50}})
            
			
			//this.Cmp.id_correspondencias_asociadas.disabled(true);
            //this.getComponente('id_correspondencias_asociadas').setVisible(false);
            
           // console.log('asociadas:   ',this.getComponente('id_correspondencias_asociadas'));
            
            //   this.getBoton('Plantilla').hide();
            this.getBoton('FinalizarExterna').hide();
            //this.getBoton('SubirDocumento').show();
            //this.getBoton('Adjuntos').show();
            this.getBoton('Corregir').hide();
            //this.getBoton('VerDocumento').show();
            this.getBoton('ImpCodigo').hide();
            this.getBoton('ImpCodigoDoc').hide();
            //this.getBoton('Derivar').hide();
            //this.getBoton('HojaRuta').hide();
            //this.getBoton('Historico').hide();
            this.getBoton('Finalizar').hide();
		
        },
      
      onButtonNew: function () {
            console.log('llega');

            console.log('inicia_eventos');
            this.cmpFechaDoc = this.getComponente('fecha_documento');
            this.Cmp.id_funcionario.store.baseParams.fecha = new Date().dateFormat(this.cmpFechaDoc.format);
            this.Cmp.id_funcionario.store.load({params:{start:0,limit:this.tam_pag},
                callback : function (r) {
                    if (r.length == 1 ) {
                        this.Cmp.id_funcionario.setValue(r[0].data.id_funcionario);
                       // this.Cmp.id_funcionario.fireEvent('select', this.Cmp.id_funcionario, r[0]);
                    }

                }, scope : this
            });

            var cmbDoc = this.getComponente('id_documento');
            var cmpFuncionarios = this.getComponente('id_funcionarios');
            //var cmpInstitucion = this.getComponente('id_institucion');
            //var cmpPersona = this.getComponente('id_persona');



            Phx.vista.Correspondencia.superclass.onButtonNew.call(this);
            this.adminGrupo({mostrar: [0, 1, 2, 3]});
            this.mostrarComponente(cmpFuncionarios);

            console.log('ver',this.Cmp);


            this.ocultarComponente(this.Cmp.id_persona_destino);
            this.ocultarComponente(this.Cmp.id_persona_remitente);
            this.ocultarComponente(this.Cmp.id_institucion_remitente);
            this.ocultarComponente(this.Cmp.id_institucion_destino);

            //this.getComponente('id_uo').enable();
            this.getComponente('id_clasificador').enable();
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
            this.getComponente('id_uo').disable();
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
                //this.getBoton('corregir').disable();
                 this.getBoton('HojaRuta').disable();
                 this.getBoton('Historico').disable();
                 this.getBoton('Archivar').disable();
            }
            if (data['estado'] == 'enviado') {
                if (tb) {

                    this.getBoton('Plantilla').disable();

                    this.getBoton('SubirDocumento').disable();
                    //this.getBoton('corregir').enable();
                    this.getBoton('Derivar').disable();
                    this.getBoton('HojaRuta').enable();
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
            //this.cmpIdInstitucion.enable();
            //this.cmpIdPersona.setVisible(false);
            //this.cmpIdInstitucion.setVisible(true);
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
		  	
	         this.cmpResponde = this.getComponente('id_correspondencias_asociadas');
             this.cmpAsocia = this.getComponente('asociar');
 
            this.cmpAsocia.on('change', function (groupRadio,radio) {
            	if(radio.inputValue){this.enableDisable(radio.inputValue);}
            },this);
            

        }
        

    };
</script>
