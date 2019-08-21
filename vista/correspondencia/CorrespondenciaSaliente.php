<?php
/**
 * @package pXP
 * @file gen-SistemaDist.php
 * @author  (fprudencio)
 * @date 11/07/2018
 * @description Archivo Correspondencia Saliente
 *
 */
#HISTORIAL DE MODIFICACIONES:
#ISSUE          FECHA        AUTOR        DESCRIPCION
#4      		25/07/2019   MCGH         Adición del campo persona_remitente, fecha recepción,
#										  Eliminación del campo id_clasificador,
#                                         Adición del campo persona_destino, fecha envio

#5      		21/08/2019   MCGH         Eliminación de Código Basura

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.CorrespondenciaSaliente = {
        bsave: false,

        require: '../../../sis_correspondencia/vista/correspondencia/Correspondencia.php',
        requireclase: 'Phx.vista.Correspondencia',
        title: 'Saliente',
        nombreVista: 'CorrespondenciaSaliente',
        fwidth: '35%',

        constructor: function (config) {

            this.Atributos[this.getIndAtributo('cite')].grid=false;
            this.Atributos[this.getIndAtributo('id_institucion_remitente')].grid=false;
            this.Atributos[this.getIndAtributo('id_persona_remitente')].grid=false;
            this.Atributos[this.getIndAtributo('otros_adjuntos')].grid=false;
            this.Atributos[this.getIndAtributo('nro_paginas')].grid=false;
            this.Atributos[this.getIndAtributo('id_funcionario_destino')].grid=false;
            this.Atributos[this.getIndAtributo('fecha_ult_derivado')].grid=false;
            this.Atributos[this.getIndAtributo('id_funcionario_saliente')].grid=false;
            this.Atributos[this.getIndAtributo('id_funcionarios')].grid=false;
            this.Atributos[this.getIndAtributo('id_documento')].grid=false;
            this.Atributos[this.getIndAtributo('asociar')].grid=false;
            this.Atributos[this.getIndAtributo('observaciones_archivado')].grid=false;
            this.Atributos[this.getIndAtributo('id_acciones')].grid=false;
            //  this.Atributos[this.getIndAtributo('persona_firma')].grid=false;
            this.Atributos[this.getIndAtributo('tipo_documento')].grid=false;



            Phx.vista.CorrespondenciaSaliente.superclass.constructor.call(this, config);

            this.getBoton('FinalizarExterna').hide();
            this.getBoton('ImpCodigo').hide();
            this.getBoton('ImpCodigoDoc').hide();
            this.getBoton('Derivar').hide();
            this.getBoton('HojaRuta').hide();
            this.getBoton('Historico').hide();
            this.getBoton('Habilitar').hide();



            this.init();
            this.argumentExtraSubmit={'vista':'CorrespondenciaInterna'};
            this.store.baseParams = {'interface': 'saliente'};
            this.load({params: {start: 0, limit: 50}})
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
            this.cmpResponde = this.getComponente('id_correspondencias_asociadas');
            this.cmpAsocia = this.getComponente('asociar');
            this.cmpAsocia.on('change', function (groupRadio,radio) {
                if(radio.inputValue){
                    this.enableDisable(radio.inputValue);
                }
            },this);
            this.Cmp.id_institucion_destino.on('select',function(combo,record,index){
                this.Cmp.id_persona_destino.store.baseParams.id_institucion=combo.getValue();
                this.Cmp.id_persona_destino.reset();
                this.Cmp.id_persona_destino.modificado=true;
            },this);

            this.Cmp.id_funcionario_saliente.on('select',function(combo, record, index){
                if(!record.data.id_uo){
                    alert('El funcionario no tiene depto definido');
                    return
                }

                //
                this.Cmp.id_uo.store.baseParams.id_fun = record.data.id_funcionario;
                this.Cmp.id_uo.store.baseParams.fecha_doc = this.getComponente('fecha_documento').value;
                //
                this.Cmp.id_uo.store.load({params:{start:0,limit:this.tam_pag},
                    callback : function (r) {
                        if (r.length == 1) {
                            this.Cmp.id_uo.setValue(r[0].data.id_uo);
                        }
                    }, scope : this
                });
            },this);
        },

        east : undefined,

        onButtonNew: function () {
            this.cmpFechaDoc = this.getComponente('fecha_documento');
            this.Cmp.id_funcionario_saliente.store.baseParams.fecha = new Date().dateFormat(this.cmpFechaDoc.format);
            var cmbDoc = this.getComponente('id_documento');
            var cmpFuncionarios = this.getComponente('id_funcionarios');

            Phx.vista.Correspondencia.superclass.onButtonNew.call(this);
            this.adminGrupo({mostrar: [0,1,2,3,4], ocultar: [1]});
            this.ocultarComponente(this.Cmp.cite);
            this.ocultarComponente(this.Cmp.otros_adjuntos);
            this.ocultarComponente(this.Cmp.nro_paginas);

            this.ocultarComponente(cmpFuncionarios);
            this.ocultarComponente(this.Cmp.id_persona_remitente);
            this.ocultarComponente(this.Cmp.id_institucion_remitente);
            this.ocultarComponente(this.Cmp.cite);
            this.ocultarComponente(this.Cmp.fecha_creacion_documento);
            this.ocultarComponente(this.Cmp.id_funcionario);
            this.ocultarComponente(this.Cmp.id_acciones);
            //  this.ocultarComponente(this.Cmp.persona_firma);
            this.ocultarComponente(this.Cmp.tipo_documento);
            this.ocultarComponente(this.Cmp.persona_remitente); //#4
            this.ocultarComponente(this.Cmp.id_persona_destino); //#4 en lugar de esto se mostrara la persona destino para typear

            this.fecha_documento = this.getComponente('fecha_documento');
            //this.fecha_documento.disable(true);

            this.tipo = this.getComponente('tipo');
            this.tipo.setValue('saliente');
            this.tipo.disable(true);

            cmbDoc.store.baseParams.tipo = 'saliente';//valor por dfecto es interna
            cmbDoc.modificado = true;
            cmbDoc.reset();

        },

        onButtonEdit: function () {
            Phx.vista.Correspondencia.superclass.onButtonEdit.call(this);
            this.adminGrupo({mostrar: [3], ocultar: [0,1,4]});
            this.ocultarComponente(this.Cmp.id_funcionarios);
            this.ocultarComponente(this.Cmp.id_persona_remitente);
            this.ocultarComponente(this.Cmp.id_institucion_remitente);
            this.ocultarComponente(this.Cmp.id_acciones);
            this.ocultarComponente(this.Cmp.id_persona_destino); //#4

            var data = this.sm.getSelected().data;


        },

        preparaMenu: function (n) {

            Phx.vista.Correspondencia.superclass.preparaMenu.call(this, n);

            var data = this.getSelectedData();
            var tb = this.tbar;
            //si el archivo esta escaneado se permite visualizar
            if (data['version'] > 0) {
                this.getBoton('VerDocumento').enable();
                this.getBoton('Finalizar').enable();

            }
            else {

                this.getBoton('VerDocumento').disable();
                this.getBoton('Finalizar').disable();
            }

            //cuando el conrtato esta registrado el abogado no puede hacerle mas cambios
            if (data['estado'] == 'borrador_envio') {

                this.getBoton('SubirDocumento').enable();
                this.getBoton('Plantilla').enable();
                this.getBoton('Adjuntos').enable();
                this.getBoton('Archivar').disable();
                this.getBoton('Corregir').disable();

            }
            if (data['estado'] == 'enviado') {
                if (tb) {

                    // this.getBoton('Plantilla').disable();
                    this.getBoton('Finalizar').disable();
                    this.getBoton('Adjuntos').disable();
                    this.getBoton('VerDocumento').enable();
                    this.getBoton('SubirDocumento').disable();
                    this.getBoton('Corregir').enable();
                    this.getBoton('edit').disable();
                    this.getBoton('del').disable();
                    this.getBoton('Archivar').enable();

                }
            }
            return tb

        }

    };
</script>
