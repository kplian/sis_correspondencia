<?php
/**
 * @package pXP
 * @file gen-Correspondencia.php
 * @author  (rac)
 * @date 13-12-2011 16:13:21
 * @description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.Correspondencia = Ext.extend(Phx.gridInterfaz, {
            bsave: false,
            fwidth: '90%',
            fheight: '90%',

            constructor: function (config) {
                this.maestro = config.maestro;
                //llama al constructor de la clase padre
                Phx.vista.Correspondencia.superclass.constructor.call(this, config);

                this.addButton('corregir', {
                    text: 'Corregir',
                    iconCls: 'bundo',
                    disabled: true,
                    handler: this.onButtonCorregir,
                    tooltip: '<b>Corregir</b><br/>Si todos los envios se detinadtarios se encuentras pendientes de lectura puede solicitar la correcion'
                });
                this.addButton('mandar', {
                    text: 'Derivar',
                    iconCls: 'badelante',
                    disabled: true,
                    handler: this.onButtonMandar,
                    tooltip: '<b>Derivar</b><br/>Despues de scanear y seleccionar los destinatarios puede derivar la correspondencia'
                });
                this.addButton('verCorrespondencia', {
                    text: 'Ver Documento',
                    iconCls: 'bsee',
                    disabled: false,
                    handler: this.verCorrespondencia,
                    tooltip: '<b>Ver archivo</b><br/>Permite visualizar el documento escaneado'
                });


                this.addButton('Hoja de Ruta', {
                    text: 'Hoja de Ruta',
                    iconCls: 'bprint',
                    disabled: false,
                    handler: this.verHojaRuta,
                    tooltip: '<b>Hoja de Ruta</b><br/>Hoja de Ruta'
                });

                this.addButton('Adjuntos', {
                    text: 'Adjuntos',
                    iconCls: 'bprint',
                    disabled: false,
                    handler: this.adjuntos,
                    tooltip: '<b>Adjuntos</b><br/>Archivos adjuntos a la correspondencia'
                });

                this.init();
                this.store.baseParams = {'interface': 'emitida'};
                this.load({params: {start: 0, limit: 50}})
                this.iniciarEventos()
            },


            east: {
                url: '../../../sis_correspondencia/vista/correspondencia_detalle/CorrespondenciaDetalle.php',
                cls: 'CorrespondenciaDetalle',
                title: 'Detalle de Correspondencia',
                height: '50%'
            },
            Atributos: [
                {
                    //configuracion del componente
                    config: {
                        labelSeparator: '',
                        inputType: 'hidden',
                        name: 'id_correspondencia'
                    },
                    type: 'Field',
                    form: true
                },


                {
                    config: {
                        name: 'version',
                        fieldLabel: 'Icono',
                        gwidth: 60,
                        renderer: function (value, p, record) {
                            var icono = record.data.estado+'.png';
                            console.log('estado',record.data.estado)
                            console.log('icono',icono)
                            if (record.data.version > 0) {
                                icono = 'good';
                            }
                            return "<div style='text-align:center'><img src = '../../../sis_correspondencia/imagenes/" + record.data.estado + ".png' align='center' width='40' height='40'/></div>"
                        }
                    },
                    type: 'Field',
                    egrid: true,
                    filters: {pfiltro: 'cor.version', type: 'numeric'},
                    id_grupo: 0,
                    grid: true,
                    form: false
                },

                {
                    config: {
                        name: 'numero',
                        fieldLabel: 'Numero',
                        gwidth: 120
                    },
                    type: 'TextField',
                    filters: {pfiltro: 'cor.numero', type: 'string'},
                    id_grupo: 0,
                    grid: true,
                    form: false
                },

                {
                    config: {
                        name: 'estado',
                        fieldLabel: 'estado',
                        allowBlank: true,
                        anchor: '80%',
                        gwidth: 100,
                        maxLength: 4
                    },
                    type: 'TextField',
                    filters: {pfiltro: 'cor.estado', type: 'numeric'},
                    id_grupo: 0,
                    grid: true,
                    form: false
                },

                {
                    config: {
                        name: 'id_archivo',
                        fieldLabel: 'Dig.',
                        gwidth: 30,
                        maxLength: 4
                    },
                    type: 'TextField',
                    //filters:{pfiltro:'cor.id_archivo',type:'numeric'},
                    id_grupo: 0,
                    grid: false,
                    form: false
                },
                {
                    config: {
                        name: 'id_depto',
                        origen: 'DEPTO',
                        allowBlank: true,
                        fieldLabel: 'Depto',
                        gdisplayField: 'desc_depto',//dibuja el campo extra de la consulta al hacer un inner join con orra tabla
                        width: 250,
                        gwidth: 200,
                        baseParams: {estado: 'activo', codigo_subsistema: 'CORRES'},//parametros adicionales que se le pasan al store
                        renderer: function (value, p, record) {
                            return String.format('{0}', record.data['desc_depto']);
                        }
                    },
                    //type:'TrigguerCombo',
                    type: 'ComboRec',
                    id_grupo: 0,
                    filters: {pfiltro: 'depto.nombre', type: 'string'},
                    grid: false,
                    form: false
                },
                {
                    config: {
                        name: 'tipo',
                        fieldLabel: 'Tipo Documento',
                        typeAhead: true,
                        allowBlank: false,
                        triggerAction: 'all',
                        emptyText: 'Seleccione Opcion...',
                        selectOnFocus: true,
                        width: 250,
                        mode: 'local',

                        store: new Ext.data.ArrayStore({
                            fields: ['ID', 'valor'],
                            data: [['interna', 'Interna'],
                                ['saliente', 'Saliente']]

                        }),
                        valueField: 'ID',
                        displayField: 'valor'

                    },
                    type: 'ComboBox',
                    valorInicial: 'interna',
                    filters: {pfiltro: 'cor.tipo', type: 'string'},
                    id_grupo: 0,
                    grid: true,
                    form: true
                },

                {
                    config: {
                        name: 'id_documento',
                        fieldLabel: 'Documento',
                        allowBlank: false,
                        emptyText: 'Documento...',
                        store: new Ext.data.JsonStore({
                            url: '../../sis_parametros/control/Documento/listarDocumento',
                            id: 'id_documento',
                            root: 'datos',
                            sortInfo: {
                                field: 'codigo',
                                direction: 'ASC'
                            },
                            totalProperty: 'total',
                            fields: ['id_documento', 'codigo', 'descripcion'],
                            // turn on remote sorting
                            remoteSort: true,
                            baseParams: {par_filtro: 'DOCUME.codigo#DOCUME.descripcion'}
                        }),
                        valueField: 'id_documento',
                        displayField: 'descripcion',
                        gdisplayField: 'desc_documento',//mapea al store del grid
                        tpl: '<tpl for="."><div class="x-combo-list-item"><p>({codigo}) {descripcion}</p> </div></tpl>',
                        hiddenName: 'id_documento',
                        forceSelection: true,
                        typeAhead: true,
                        triggerAction: 'all',
                        lazyRender: true,
                        mode: 'remote',
                        pageSize: 10,
                        queryDelay: 1000,
                        width: 250,
                        gwidth: 150,
                        minChars: 2,
                        renderer: function (value, p, record) {
                            return String.format('{0}', record.data['desc_documento']);
                        }
                    },
                    type: 'ComboBox',
                    id_grupo: 0,
                    filters: {
                        pfiltro: 'doc.descripcion',
                        type: 'string'
                    },

                    grid: true,
                    form: true
                },
                {
                    config: {
                        name: 'fecha_documento',
                        fieldLabel: 'Fecha Documento',
                        disabled: true,
                        allowBlank: false,
                        format: 'd-m-Y',
                        width: 100,
                        gwidth: 100,
                        renderer: function (value, p, record) {
                            return value ? value.dateFormat('d/m/Y') : ''
                        }
                    },
                    type: 'DateField',
                    valorInicial: new Date(),
                    filters: {pfiltro: 'cor.fecha_documento', type: 'date'},
                    id_grupo: 2,
                    grid: true,
                    form: true
                },


                {
                    config: {
                        name: 'id_funcionario',
                        origen: 'FUNCIONARIOCAR',
                        fieldLabel: 'Funcionario Remitente',
                        gdisplayField: 'desc_funcionario',//mapea al store del grid
                        valueField: 'id_funcionario',

                        gwidth: 200,
                        baseParams: {es_combo_solicitud: 'si'},
                        renderer: function (value, p, record) {
                            return String.format('{0}', record.data['desc_funcionario']);
                        }
                    },
                    type: 'ComboRec',
                    id_grupo: 1,
                    filters: {
                        pfiltro: 'desc_funcionario1',
                        type: 'string'
                    },

                    grid: true,
                    form: true
                },
                {
                    config: {
                        name: 'id_uo',
                        baseParams: {correspondencia: 'si'},
                        origen: 'UO',
                        fieldLabel: 'UO Remitente',
                        gdisplayField: 'desc_uo',//mapea al store del grid
                        gwidth: 200,
                        renderer: function (value, p, record) {
                            return String.format('{0}', record.data['desc_uo']);
                        }
                    },
                    type: 'ComboRec',
                    id_grupo: 1,
                    filters: {
                        pfiltro: 'desc_uo',
                        type: 'string'
                    },
                    grid: true,
                    form: false
                },

                {
                    config: {
                        name: 'id_institucion_destino',
                        allowBlank: true,
                        fieldLabel: 'Institucion',
                        anchor: '90%',
                        tinit: true,
                        origen: 'INSTITUCION',
                        gdisplayField: 'nombre',
                        gwidth: 200,
                        renderer: function (value, p, record) {
                            return String.format('{0}', record.data['nombre']);
                        }

                    },
                    type: 'ComboRec',
                    id_grupo: 3,
                    filters: {pfiltro: 'nombre', type: 'string'},
                    grid: false,
                    form: true
                },

                {
                    config: {
                        name: 'id_persona_destino',
                        origen: 'PERSONA',
                        tinit: true,
                        fieldLabel: 'Persona',
                        gdisplayField: 'desc_person',//mapea al store del grid
                        gwidth: 200,
                        renderer: function (value, p, record) {
                            return String.format('{0}', record.data['desc_person']);
                        }
                    },
                    type: 'ComboRec',
                    id_grupo: 3,
                    filters: {
                        pfiltro: 'PERSON.nombre_completo1',
                        type: 'string'
                    },

                    grid: false,
                    form: true
                },

                {
                    config: {
                        name: 'id_funcionarios',
                        fieldLabel: 'Funcionario(s). Destino',
                        allowBlank: true,
                        emptyText: 'Funcionarios...',
                        store: new Ext.data.JsonStore({
                            url: '../../sis_organigrama/control/Funcionario/listarFuncionarioCargo',
                            id: 'id_funcionario',
                            root: 'datos',
                            sortInfo: {
                                field: 'desc_funcionario1',
                                direction: 'ASC'
                            },
                            totalProperty: 'total',
                            fields: ['id_funcionario', 'id_uo', 'codigo', 'nombre_cargo', 'desc_funcionario1', 'email_empresa'],
                            // turn on remote sorting
                            remoteSort: true,
                            baseParams: {
                                par_filtro: 'desc_funcionario1#email_empresa#codigo#nombre_cargo',
                                estado_reg_asi: 'activo'
                            }

                        }),
                        tpl: '<tpl for="."><div class="x-combo-list-item" ><div class="awesomecombo-item {checked}">{codigo}-{desc_funcionario1}</div><p style="padding-left: 20px;">{nombre_cargo}</p><p style="padding-left: 20px;">{email_empresa}</p> </div></tpl>',
                        valueField: 'id_funcionario',
                        displayField: 'desc_funcionario1',
                        hiddenName: 'id_funcionarios',
                        typeAhead: true,
                        triggerAction: 'all',
                        lazyRender: true,
                        mode: 'remote',
                        pageSize: 10,
                        queryDelay: 1000,
                        width: 250,
                        minChars: 2,
                        enableMultiSelect: true
                    },
                    type: 'AwesomeCombo',
                    id_grupo: 3,
                    grid: false,
                    form: true
                },


                {
                    config: {
                        name: 'referencia',
                        fieldLabel: 'referencia',
                        allowBlank: true,
                        width: 300,
                        growMin: 100,
                        grow: true,
                        gwidth: 100,
                        maxLength: 500
                    },
                    type: 'TextArea',
                    filters: {pfiltro: 'cor.referencia', type: 'string'},
                    id_grupo: 2,
                    grid: true,
                    form: true
                },

                {
                    config: {
                        name: 'mensaje',
                        fieldLabel: 'mensaje',
                        allowBlank: true,
                        width: 300,
                        growMin: 100,
                        grow: true,
                        gwidth: 100
                    },
                    type: 'TextArea',
                    filters: {pfiltro: 'cor.mensaje', type: 'string'},
                    id_grupo: 2,
                    grid: true,
                    form: true
                },
                {
                    config: {
                        name: 'id_acciones',
                        fieldLabel: 'Acciones',
                        allowBlank: false,
                        emptyText: 'Acciones...',
                        store: new Ext.data.JsonStore({
                            url: '../../sis_correspondencia/control/Accion/listarAccion',
                            id: 'id_accion',
                            root: 'datos',
                            sortInfo: {
                                field: 'nombre',
                                direction: 'ASC'
                            },
                            totalProperty: 'total',
                            fields: ['id_accion', 'nombre'],
                            // turn on remote sorting
                            remoteSort: true,
                            baseParams: {par_filtro: 'acco.nombre'}
                        }),
                        valueField: 'id_accion',
                        displayField: 'nombre',
                        gdisplayField: 'desc_acciones',//mapea al store del grid

                        hiddenName: 'id_acciones',
                        forceSelection: true,
                        typeAhead: true,
                        triggerAction: 'all',
                        lazyRender: true,
                        mode: 'remote',
                        pageSize: 10,
                        queryDelay: 1000,
                        width: 250,
                        gwidth: 200,
                        minChars: 2,
                        enableMultiSelect: true,
                        renderer: function (value, p, record) {
                            return String.format('{0}', record.data['desc_acciones']);
                        }
                    },
                    type: 'AwesomeCombo',
                    id_grupo: 3,
                    grid: false,
                    form: true
                },


                {
                    config: {
                        name: 'id_correspondencias_asociadas',
                        fieldLabel: 'Responde a',
                        allowBlank: true,
                        emptyText: 'Correspondencias...',
                        store: new Ext.data.JsonStore({
                            url: '../../sis_correspondencia/control/Correspondencia/listarCorrespondenciaSimplificada',
                            id: 'id_correspondencia',
                            root: 'datos',
                            sortInfo: {
                                field: 'id_correspondencia',
                                direction: 'desc'
                            },
                            totalProperty: 'total',
                            fields: ['id_correspondencia', 'numero', 'referencia', 'desc_funcionario1'],
                            // turn on remote sorting
                            remoteSort: true,
                            baseParams: {par_filtro: 'cor.numero#cor.referencia#funcionario.desc_funcionario1'}
                        }),
                        valueField: 'id_correspondencia',
                        displayField: 'numero',
                        gdisplayField: 'desc_asociadas',//mapea al store del grid

                        hiddenName: 'id_correspondencias_asociadas',
                        forceSelection: true,
                        typeAhead: true,
                        triggerAction: 'all',
                        enableMultiSelect: true,
                        lazyRender: true,
                        mode: 'remote',
                        pageSize: 10,
                        queryDelay: 1000,
                        width: 250,
                        gwidth: 200,
                        minChars: 2,
                        renderer: function (value, p, record) {
                            return String.format('{0}', record.data['desc_asociadas']);
                        }
                    },
                    type: 'AwesomeCombo',
                    id_grupo: 2,
                    /*filters:{
                     pfiltro:'acco.desc_asociadas',
                     type:'string'
                     },*/

                    grid: false,
                    form: true
                },

                {
                    config: {
                        name: 'nivel_prioridad',
                        fieldLabel: 'Nivel de Prioridad',
                        typeAhead: true,
                        allowBlank: false,
                        triggerAction: 'all',
                        emptyText: 'Seleccione Opcion...',
                        selectOnFocus: true,
                        mode: 'local',
                        //valorInicial:{ID:'interna',valor:'Interna'},
                        store: new Ext.data.ArrayStore({
                            fields: ['ID', 'valor'],
                            data: [['alta', 'Alta'],
                                ['media', 'Media'],
                                ['baja', 'Baja']]
                        }),
                        valueField: 'ID',
                        displayField: 'valor',
                        width: 150

                    },
                    type: 'ComboBox',
                    valorInicial: 'media',
                    filters: {pfiltro: 'cor.nivel_prioridad', type: 'string'},
                    id_grupo: 2,
                    grid: true,
                    form: true
                },

                {
                    config: {
                        name: 'id_clasificador',
                        origen: 'CLASIFICADOR',
                        fieldLabel: 'Clasificación',
                        gdisplayField: 'desc_clasificador',//mapea al store del grid
                        gwidth: 200,
                        renderer: function (value, p, record) {
                            return String.format('{0}', record.data['desc_clasificador']);
                        }

                    },
                    type: 'ComboRec',
                    id_grupo: 2,
                    filters: {
                        pfiltro: 'tclasificador.nombre',
                        type: 'string'
                    },

                    grid: true,
                    form: true
                }
                ,
                {
                    config: {
                        name: 'respuestas',
                        fieldLabel: 'respuestas',
                        allowBlank: true,
                        gwidth: 150,
                        maxLength: 500
                    },
                    type: 'TextField',
                    filters: {pfiltro: 'cor.respuestas', type: 'string'},
                    id_grupo: 1,
                    grid: false,
                    form: false
                },
                {
                    config: {
                        name: 'fecha_reg',
                        fieldLabel: 'Fecha creación',
                        allowBlank: true,
                        anchor: '80%',
                        gwidth: 100,
                        renderer: function (value, p, record) {
                            return value ? value.dateFormat('d/m/Y h:i:s') : ''
                        }
                    },
                    type: 'DateField',
                    gwidth: 100,
                    filters: {pfiltro: 'cor.fecha_reg', type: 'date'},
                    id_grupo: 2,
                    grid: true,
                    form: false
                },

                {
                    config: {
                        name: 'usr_reg',
                        fieldLabel: 'Creado por',
                        allowBlank: true,

                        gwidth: 100,
                        maxLength: 4
                    },
                    type: 'TextField',
                    filters: {pfiltro: 'usu1.cuenta', type: 'string'},
                    id_grupo: 2,
                    grid: true,
                    form: false
                },

                {
                    config: {
                        name: 'estado_reg',
                        fieldLabel: 'Estado Reg.',
                        allowBlank: false,

                        gwidth: 100,
                        maxLength: 10
                    },
                    type: 'TextField',
                    filters: {pfiltro: 'cor.estado_reg', type: 'string'},
                    id_grupo: 2,
                    grid: true,
                    form: false
                },
                {
                    config: {
                        name: 'fecha_mod',
                        fieldLabel: 'Fecha Modif.',
                        allowBlank: true,

                        gwidth: 100,
                        renderer: function (value, p, record) {
                            return value ? value.dateFormat('d/m/Y h:i:s') : ''
                        }
                    },
                    type: 'DateField',
                    filters: {pfiltro: 'cor.fecha_mod', type: 'date'},
                    id_grupo: 2,
                    grid: true,
                    form: false
                },
                {
                    config: {
                        name: 'usr_mod',
                        fieldLabel: 'Modificado por',
                        allowBlank: true,

                        gwidth: 100,
                        maxLength: 4
                    },
                    type: 'TextField',
                    filters: {pfiltro: 'usu2.cuenta', type: 'string'},
                    id_grupo: 12,
                    grid: true,
                    form: false
                }
            ],
            title: 'Correspondencia',
            ActSave: '../../sis_correspondencia/control/Correspondencia/insertarCorrespondencia',
            ActDel: '../../sis_correspondencia/control/Correspondencia/eliminarCorrespondencia',
            ActList: '../../sis_correspondencia/control/Correspondencia/listarCorrespondencia',
            id_store: 'id_correspondencia',
            fields: [
                'id_correspondencia',
                'estado',
                'estado_reg',
                {name: 'fecha_documento', type: 'date', dateFormat: 'Y-m-d'},
                {name: 'fecha_fin', type: 'date', dateFormat: 'Y-m-d H:i:s'},
                'id_acciones',
                'id_archivo',
                'id_correspondencia_fk',
                'id_correspondencias_asociadas',
                'id_depto',
                'id_documento',
                'id_funcionario',
                'id_gestion',
                'id_institucion',
                'id_periodo',
                'id_persona',
                'id_uo',
                'mensaje',
                'nivel',
                'nivel_prioridad',
                'numero',
                'observaciones_estado',
                'referencia',
                'respuestas',
                'sw_responsable',
                'tipo',
                {name: 'fecha_reg', type: 'date', dateFormat: 'Y-m-d H:i:s'},
                'id_usuario_reg',
                {name: 'fecha_mod', type: 'date', dateFormat: 'Y-m-d H:i:s'},
                'id_usuario_mod', 'usr_reg',
                'usr_mod', 'cite', 'desc_depto', 'desc_documento', 'desc_funcionario', 'ruta_archivo', 'version',
                'desc_uo',
                'id_clasificador', 'desc_clasificador',
                'id_origen',
                'sw_archivado'
            ],
            sortInfo: {
                field: 'id_correspondencia',
                direction: 'desc'
            },
            Grupos: [
                {
                    layout: 'column',
                    border: false,
                    defaults: {
                        border: false
                    },
                    items: [
                        {
                            bodyStyle: 'padding-left:5px;padding-left:5px;',
                            border: false,
                            defaults: {
                                border: false
                            },
                            width: 500,
                            items: [

                                {
                                    bodyStyle: 'padding-left:5px;padding-left:5px;',
                                    items: [{
                                        xtype: 'fieldset',
                                        title: 'Parametros',

                                        //autoHeight: true,
                                        items: [],
                                        id_grupo: 0
                                    }]
                                }, {
                                    bodyStyle: 'padding-left:5px;padding-left:5px;',
                                    items: [{
                                        xtype: 'fieldset',
                                        title: 'Datos Remitente',

                                        // autoHeight: true,
                                        items: [],
                                        id_grupo: 1
                                    }]
                                },
                                {
                                    bodyStyle: 'padding-left:5px;padding-left:5px;',
                                    items: [{
                                        xtype: 'fieldset',

                                        title: 'Datos Destinatario',
                                        // autoHeight: true,
                                        items: [],
                                        id_grupo: 3
                                    }]
                                }
                            ]
                        },
                        {
                            bodyStyle: 'padding-left:5px;padding-left:5px;',
                            border: false,
                            hidden: false,
                            width: 500,
                            items: [{
                                xtype: 'fieldset',
                                title: 'Mensaje',
                                //autoHeight: true,
                                items: [],
                                id_grupo: 2
                            }]
                        }]
                }

            ],

            loadValoresIniciales: function () {

                Phx.vista.Correspondencia.superclass.loadValoresIniciales.call(this);


            },


            verCorrespondencia: function () {
                var rec = this.sm.getSelected();
                console.log('rec', rec);

                Ext.Ajax.request({
                    // form:this.form.getForm().getEl(),
                    url: '../../sis_correspondencia/control/Correspondencia/verCorrespondencia',
                    params: {id_origen: rec.data.id_origen},
                    success: this.successVer,
                    failure: this.conexionFailure,
                    timeout: this.timeout,
                    scope: this
                });

            },

            successVer: function (resp) {

                var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
                console.log(reg.datos[0].ruta_archivo);
                window.open(reg.datos[0].ruta_archivo);
            },
            onButtonCorregir: function () {
                var id_correspondencia = this.sm.getSelected().data.id_correspondencia;
                Phx.CP.loadingShow();
                Ext.Ajax.request({
                    // form:this.form.getForm().getEl(),
                    url: '../../sis_correspondencia/control/Correspondencia/corregirCorrespondencia',
                    params: {id_correspondencia: id_correspondencia},
                    success: this.successDerivar,
                    failure: this.conexionFailure,
                    timeout: this.timeout,
                    scope: this
                });
            },

            onButtonMandar: function () {

                var data = this.sm.getSelected().data.id_proceso_contrato;
                var id_correspondencia = this.sm.getSelected().data.id_correspondencia;
                Phx.CP.loadingShow();
                Ext.Ajax.request({
                    // form:this.form.getForm().getEl(),
                    url: '../../sis_correspondencia/control/Correspondencia/derivarCorrespondencia',
                    params: {id_correspondencia: id_correspondencia},
                    success: this.successDerivar,
                    failure: this.conexionFailure,
                    timeout: this.timeout,
                    scope: this
                });
            },
            successDerivar: function (resp) {

                Phx.CP.loadingHide();
                var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
                if (!reg.ROOT.error) {
                    alert(reg.ROOT.detalle.mensaje)

                } else {

                    alert('ocurrio un error durante el proceso')
                }
                this.reload();

            },


            obtenerGestion: function (x) {

                var fecha = x.getValue().dateFormat(x.format);
                Phx.CP.loadingShow();
                Ext.Ajax.request({
                    // form:this.form.getForm().getEl(),
                    url: '../../sis_parametros/control/Gestion/obtenerGestionByFecha',
                    params: {fecha: fecha},
                    success: this.successGestion,
                    failure: this.conexionFailure,
                    timeout: this.timeout,
                    scope: this
                });
            },

            successGestion: function (resp) {
                console.log(resp)
                Phx.CP.loadingHide();
                var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
                if (!reg.ROOT.error) {
                    this.cmpIdGestion.setValue(reg.ROOT.datos.id_gestion);

                } else {
                    alert('ocurrio al obtener la gestion')
                }
            },
            verHojaRuta:function(){

                var rec = this.sm.getSelected();

                Ext.Ajax.request({
                    url: '../../sis_correspondencia/control/Correspondencia/hojaRuta',
                    params: {
                        id_correspondencia: rec.data.id_correspondencia,
                        start:0,limit:1
                    },
                    success: this.successHojaRuta,
                    failure: this.conexionFailure,
                    timeout: this.timeout,
                    scope: this
                });

            },
            successHojaRuta:function(resp){
                var objRes = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
                var texto = objRes.datos.html;
                ifrm = document.createElement("IFRAME");
                ifrm.name = 'mifr';
                ifrm.id = 'mifr';
                document.body.appendChild(ifrm);
                var doc = window.frames['mifr'].document;
                doc.open();
                doc.write(texto);
                doc.close();
            },
            adjuntos:function(){
                var rec = this.sm.getSelected();

                Phx.CP.loadWindows('../../../sis_correspondencia/vista/adjunto/Adjunto.php',
                    'Interfaces',
                    {
                        width: 900,
                        height: 400
                    }, rec.data, this.idContenedor, 'Adjunto')
            }


        }
    )
</script>

