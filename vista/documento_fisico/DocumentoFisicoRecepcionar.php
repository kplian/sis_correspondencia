<?php
/**
 * @package pXP
 * @file gen-SistemaDist.php
 * @author  (favio figueroa)
 * @date 20-09-2011 10:22:05
 * @description Archivo con la interfaz de usuario que permite
 *
 */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.DocumentoFisicoRecepcionar = {
        bsave: false,
        bnew: false,
        bedit: false,
        bdel: false,
        require: '../../../sis_correspondencia/vista/documento_fisico/DocumentoFisico.php',
        requireclase: 'Phx.vista.DocumentoFisico',
        title: 'Documentos Fisicos Recepcionar',
        nombreVista: 'DocumentoFisicoRecepcionar',

        //ActList:'../../sis_correspondencia/control/DocumentoFisico/listarDocumentoFisicoRecepcionar',

        swEstado: 'despachado',
        vista_documento_fisico:'recepcionar',
        gruposBarraTareas: [{
            name: 'despachado',
            title: '<H1 align="center"><i class="fa fa-thumbs-o-down"></i> Despachados</h1>',
            grupo: 0,
            height: 0
        },
            {
                name: 'recepcionado',
                title: '<H1 align="center"><i class="fa fa-eye"></i> Recepcionados</h1>',
                grupo: 1,
                height: 0
            },
        ],


        beditGroups: [0, 1, 2],
        bactGroups: [0, 1, 2],
        btestGroups: [0],
        bexcelGroups: [0, 1, 2],

        constructor: function (config) {
            Phx.vista.DocumentoFisicoRecepcionar.superclass.constructor.call(this, config);


            this.store.baseParams = {'estado': 'despachado',vista_documento_fisico:'recepcionar'};
            this.load({params: {start: 0, limit: 50}});

            /*this.addButton('despachar', {
                text: 'despachar',
                iconCls: 'bgood',
                disabled: true,
                handler: this.despachar,
                tooltip: '<b>despachar</b><br/>Permite despachar la correspondencia fisica'
            });*/

            this.addButton('recepcionar', {
                grupo: [0],
                text: 'recepcionar',
                iconCls: 'bchecklist',
                disabled: true,
                handler: this.recepcionar,
                tooltip: '<b>recepcionar</b><br/>Permite despachar la correspondencia fisica'
            });

            this.addButton('despachar', {
                grupo: [1],
                text: 'Volver a despachar',
                iconCls: 'bchecklist',
                disabled: true,
                handler: this.despachar,
                tooltip: '<b>despachar</b><br/>Permite volver a pendiente la correspondencia fisica'
            });


            this.init();
            this.finCons = true;


        },
        preparaMenu: function (n) {

            Phx.vista.DocumentoFisicoRecepcionar.superclass.preparaMenu.call(this, n);
            var data = this.getSelectedData();

            console.log('data', data)
            var tb = this.tbar;


            if (data.estado == 'despachado') {
                this.getBoton('recepcionar').enable();
            } else {
                this.getBoton('recepcionar').disable();
            }


            if (data.estado == 'recepcionado') {
                this.getBoton('despachar').enable();
            } else {
                this.getBoton('despachar').disable();
            }

            //si el archivo esta escaneado se permite visualizar


            return tb

        },
        getParametrosFiltro: function () {
            this.store.baseParams.estado = this.swEstado;
        },

        actualizarSegunTab: function (name, indice) {
            console.log(name);

            this.swEstado = name;
            this.getParametrosFiltro();
            Phx.vista.DocumentoFisicoRecepcionar.superclass.onButtonAct.call(this);


        },

        despachar: function () {
            var rec = this.sm.getSelected();

            Ext.Ajax.request({
                url: '../../sis_correspondencia/control/DocumentoFisico/cambiarEstado',
                params: {
                    id_documento_fisico: rec.data.id_documento_fisico,
                    estado: 'despachado'
                },
                success: this.successDespachar,
                failure: this.conexionFailure,
                timeout: this.timeout,
                scope: this
            });


        },
        successDespachar: function (resp) {

            this.load();

        },
        recepcionar: function () {
            var rec = this.sm.getSelected();

            Ext.Ajax.request({
                url: '../../sis_correspondencia/control/DocumentoFisico/cambiarEstado',
                params: {
                    id_documento_fisico: rec.data.id_documento_fisico,
                    estado: 'recepcionado'
                },
                success: this.successDespachar,
                failure: this.conexionFailure,
                timeout: this.timeout,
                scope: this
            });


        },


    };
</script>
