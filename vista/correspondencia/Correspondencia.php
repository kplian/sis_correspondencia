<?php
/**
 * @file gen-Correspondencia.php
 * @author  ()
 * @date 13-12-2011 16:13:21
 * @description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 */

#HISTORIAL DE MODIFICACIONES:
#ISSUE          FECHA        AUTOR        DESCRIPCION
#4      		25/07/2019   MCGH         Adición del campo persona_remitente, fecha recepción,
#										  Eliminación del campo id_clasificador,
#										  Adición del campo persona_destino

#5      		21/08/2019   MCGH         Eliminación de Código Basura
#6      		02/09/2019   MCGH         Correcciones a observaciones de forma
#7      		06/09/2019   MCGH         Adición del campo Tiene el Fisico

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.Correspondencia = Ext.extend(Phx.gridInterfaz, {
        bsave : false,
        fwidth : '10%',
        fheight : '90%',
        urlDepto : '../../sis_parametros/control/Depto/listarDeptoFiltradoXUsuario',

        constructor : function(config) {
            this.maestro = config.maestro;
            //llama al constructor de la clase padre
            Phx.vista.Correspondencia.superclass.constructor.call(this, config);


            //Botones.
            //7
            this.addButton('ImpCodigo', {
                text: 'Imprimir Cite',
                iconCls: 'bprint',
                disabled: true,
                handler: this.BImpCodigo,
                tooltip: '<b>Imprimir Codigo</b><br/>imprimir codigo correspondencia'
            });
            //1
            this.addButton('Plantilla', {
                text: 'Plantilla',
                iconCls: 'bword',
                disabled: false,
                handler: this.BPlantilla,
                tooltip: '<b>PlantillaCorrespondencia</b><br/>visualiza la plantilla correspondencia'
            });
            //2
            this.addButton('FinalizarExterna', {
                text: 'Finalizar Recepcion',
                iconCls: 'bgood',
                disabled: true,
                handler: this.BFinalizarExterna,
                tooltip: '<b>Finalizar Recepción</b><br/>Finalizar Recepción de documento entrante (Externa Recibida), pasa a estado de análisis'
            });
            //3
            this.addButton('SubirDocumento', {
                text: 'Subir Documento',
                iconCls: 'bupload',
                disabled: true,
                handler: this.BSubirDocumento,
                tooltip: '<b>Subir archivo</b><br/>Permite actualizar el documento escaneado'
            });
            //4
            this.addButton('Adjuntos', {
                text : 'Adjuntos',
                iconCls : 'badjunto',
                disabled : true,
                handler : this.BAdjuntos,
                tooltip : '<b>Adjuntos</b><br/>Archivos adjuntos a la correspondencia'
            });
            //6
            this.addButton('VerDocumento', {
                grupo : [0, 1],
                text : 'Ver Documento',
                iconCls : 'bsee',
                disabled : true,
                handler : this.BVerDocumento,
                tooltip : '<b>Ver archivo</b><br/>Permite visualizar el documento escaneado'
            });

            //8
            this.addButton('ImpCodigoDoc', {
                text: 'Imprimir Documento',
                iconCls: 'bprintcheck',
                disabled: false,
                handler: this.BImpCodigoDoc,
                tooltip: '<b>Imprimir Codigo</b><br/>imprimir codigo correspondencia'

            });
            //9
            this.addButton('Derivar', {
                text : 'Derivar',
                iconCls : 'badelante',
                disabled : true,
                handler : this.BDerivar,
                tooltip : '<b>Derivar</b><br/>Despues de scanear y seleccionar los destinatarios puede derivar la correspondencia'
            });
            //10
            this.addButton('HojaRuta', {
                text : 'Hoja de Recepción',
                iconCls : 'bprint',
                disabled : true,
                handler : this.BHojaRuta,
                tooltip : '<b>Hoja de Recepción</b><br/>Hoja de Recepción'
            });
            //11
            this.addButton('Historico', {
                text : 'Histórico',
                iconCls : 'bmenuitems',
                disabled : true,
                handler : this.BHistorico,
                tooltip : '<b>Histórico</b><br/>De Derivaciones realizadas'
            });
            this.addButton('Finalizar', {
                text: 'Finalizar Envío',
                iconCls: 'bgood',
                disabled: true,
                handler: this.BFinalizar,
                tooltip: '<b>Finalizar Envio</b><br/>Finalizar el evío del Documento'
            });

            this.addButton('Archivar', {
                grupo : [0,1],
                text: 'Archivar',
                iconCls: 'bsave',
                disabled: true,
                handler: this.BArchivar,
                tooltip: '<b>Archivar</b><br/>'
            });
            this.addButton('Habilitar', {

                text: 'Habilitar',
                iconCls: 'bgood',
                disabled: true,
                handler: this.BHabilitar,
                tooltip: '<b>Habilitar Anulados</b><br/>'
            });
            //5
            this.addButton('Corregir', {

                grupo : [0,1],
                text : 'Corregir',
                iconCls : 'bundo',
                disabled : true,
                hidden : true,
                handler : this.BCorregir,
                tooltip : '<b>Corregir</b><br/>Si todos los envios de destinatarios se encuentran pendientes de lectura puede solicitar la corrección'
            });
            this.iniciarEventos()
        },

        east : {

            url: '../../../sis_correspondencia/vista/correspondencia_detalle/CorrespondenciaDetalle.php',

            cls : 'CorrespondenciaDetalle',
            title : 'Detalle de Derivación',
            height : '50%'
        },
        Atributos : [{
            //configuracion del componente
            config : {
                labelSeparator : '',
                inputType : 'hidden',
                name : 'id_correspondencia'
            },
            type : 'Field',
            form : true
        },  {
            config : {
                name : 'archivado_imagen',
                fieldLabel : 'Archivado',
                gwidth : 50,
                renderer : function(value, p, record) {
                    if (record.data.sw_archivado=='si'){
                        var icono = record.data.sw_archivado + '.png';
                        return "<div style='text-align:center'><img src = '../../../sis_correspondencia/imagenes/" + icono +"' align='center' width='40' height='40' title='"+record.data.observaciones_archivado+"' /></div>"
                    }
                }
            },
            type : 'Field',
            tooltip : '<b>Corregir</b><br/>Si todos los envios de destinatarios se encuentran pendientes de lectura puede solicitar la corrección',
            filters : {
                pfiltro : 'cor.sw_archivado',
                type : 'string'
            },
            form:false
        }
            ,{
                config : {
                    name : 'nivel_prioridad_imagen',
                    fieldLabel : 'Prioridad',
                    gwidth : 30,
                    renderer : function(value, p, record) {
                        var icono = record.data.nivel_prioridad + '.png';
                        var prioridad = '';
                        if (record.data.nivel_prioridad=='1alta'){
                            prioridad='Alta';
                        }else if (record.data.nivel_prioridad=='2media'){
                            prioridad='Media';
                        }else{
                            prioridad='Baja';

                        }

                        return "<div style='text-align:center'><img title="+prioridad+" src = '../../../sis_correspondencia/imagenes/" + icono + "' align='center' width='10' height='25'/></div>"
                    }
                },
                type : 'Field',
                egrid : false,
                filters : {
                    pfiltro : 'cor.nivel_prioridad',
                    type : 'string'
                },
                grid : true,
                form : false
            },

            {
                config : {
                    name : 'version',
                    fieldLabel : 'Icono',
                    gwidth : 60,
                    renderer : function(value, p, record) {
                        var icono = record.data.estado + '.png';
                        var estado = '';
                        switch (record.data.estado){
                            case 'borrador_recepcion_externo': estado='Borrador';
                                break;
                            case 'pendiente_recepcion_externo': estado='Pendiente Externo';
                                break;
                            case 'enviado': estado='Enviado';
                                break;
                            case 'borrador_envio':  estado='Borrador';
                                break;
                            case 'pendiente_recibido': estado='Pendiente de Recepción';
                                break;
                            case 'recibido': estado='Recibido';
                                break;
                            case 'anulado': estado='Anulado';
                                break;

                        };
                        if (record.data.estado == 'borrador_recepcion_externo' || record.data.estado=='borrador_envio') {
                            if (record.data.version>0) {
                                estado='Borrador, Documento Principal Subido Correctamente '
                                return "<div style='text-align:center'><img title='"+estado+"' src = '../../../sis_correspondencia/imagenes/borrador_recepcion_externo_adjdoc.png' align='center' width='40' height='40'/></div>"
                            }else{
                                return "<div style='text-align:center'><img title='"+estado+"' src = '../../../sis_correspondencia/imagenes/" + icono + "' align='center' width='40' height='40'/></div>"
                            }
                        }else{
                            return "<div style='text-align:center'><img title='"+estado+"' src = '../../../sis_correspondencia/imagenes/" + icono + "' align='center' width='40' height='40'/></div>"
                        }
                    }
                },
                type : 'Field',
                egrid : false,
                filters : {
                    pfiltro : 'cor.estado',
                    type : 'numeric'
                },
                id_grupo : 0,
                grid : true,
                form : false
            }, {
                config : {
                    name : 'adjunto',
                    fieldLabel : 'Adjunto',
                    gwidth : 40,
                    renderer : function(value, p, record) {
                        var icono = record.data.estado + '.png';
                        if (record.data.adjunto > 0) {
                            return "<div style='text-align:center'><img title='Cantidad de Adjuntos: "+record.data.adjunto+"' src = '../../../sis_correspondencia/imagenes/adjunto.png' align='center' width='20' height='20'/></div>";
                        }
                    }
                },
                type : 'Field',
                egrid : false,
                id_grupo : 0,
                grid : true,
                form : false
            },{
                config : {
                    name : 'numero',
                    fieldLabel : 'Numero',
                    gwidth : 80
                },
                type : 'TextField',
                filters : {
                    pfiltro : 'cor.numero',
                    type : 'string'
                },
                id_grupo : 0,
                grid : true,
                form : false,
                bottom_filter : true
            },
            { //#7
                config : {
                    name : 'fisico',
                    fieldLabel : '¿Tiene el Fisico?',
                    gwidth : 80,
                    renderer:function (value, p, record){
                        if(record.data['fisico']=='si')
                            return String.format('<b><font size="5" color="red">{0}</font></b>', record.data['fisico']);
                        else
                            return String.format('{0}', record.data['fisico']);

                    }
                },
                type : 'TextField',
                filters : {
                    pfiltro : 'cor.fisico',
                    type : 'string'
                },
                id_grupo : 0,
                grid : true,
                form : false,
                bottom_filter : true
            },
            {
                config : {
                    name : 'cite',
                    fieldLabel : 'Cite',
                    allowBlank : false, //#6
                    gwidth : 80,
                    width : 300
                },
                type : 'TextField',
                filters : {
                    pfiltro : 'cor.cite',
                    type : 'string'
                },

                id_grupo : 1,

                grid : true,
                form : true,
                bottom_filter : true
            },
            {
                config : {
                    name : 'id_institucion_remitente',
                    allowBlank : false, //#6
                    fieldLabel : 'Institucion Remitente',
                    valueField : 'id_institucion',
                    //anchor : '90%',
                    tinit : true,
                    origen : 'INSTITUCION',
                    gdisplayField : 'desc_insti',
                    width : 200,
                    gwidth : 200,
                    renderer : function(value, p, record) {
                        return String.format('{0}', record.data['desc_insti']);
                    }
                },
                type : 'ComboRec',
                id_grupo : 1,
                filters : {
                    pfiltro : 'insti.nombre',
                    type : 'string'
                },
                grid : true,
                form : true,
                bottom_filter : true
            }, {
                config : {
                    name : 'persona_remitente',
                    fieldLabel : 'Persona Remitente',
                    gwidth : 100,
                    width : 300
                },
                type : 'TextField',
                filters : {
                    pfiltro : 'cor.persona_remitente',
                    type : 'string'
                },

                id_grupo : 1,

                grid : true,
                form : true,
                bottom_filter : true
            },{
                config : {
                    name : 'id_persona_remitente',
                    origen : 'PERSONA',
                    allowBlank : true,
                    tinit : true,
                    fieldLabel : 'Persona Remitente',
                    gdisplayField : 'nombre_completo1', //mapea al store del grid
                    valueField : 'id_persona',
                    gwidth : 200,
                    width : 300,
                    renderer : function(value, p, record) {
                        return String.format('{0}', record.data['nombre_completo1']);
                    }
                },
                type : 'ComboRec',
                id_grupo : 1,
                filters : {
                    //pfiltro : 'persona.nombre_completo1',
                    pfiltro : 'persona.nombre_completo1',
                    type : 'string'
                },

                grid : false,
                form : true,
                bottom_filter : true
            },

            {
                config : {
                    name : 'referencia',
                    fieldLabel : 'Referencia',
                    allowBlank : true,
                    width : 200,
                    growMin : 100,
                    grow : true,
                    gwidth : 200,
                    maxLength : 500,
                    renderer : function(value, p, record) {
                        return '<span title="'+value+'">'+value+'</span>';
                    },
                },
                type : 'TextArea',
                filters : {
                    pfiltro : 'cor.referencia',
                    type : 'string'
                },
                id_grupo : 2,
                grid : true,
                form : true,
                bottom_filter : true
            },
            {
                config : {
                    name : 'otros_adjuntos',
                    fieldLabel : 'Descripción de Adjuntos',
                    width : 200,
                    growMin : 100,
                    grow : true,
                    gwidth : 200,
                    renderer : function(value, p, record) {
                        return '<span title="'+value+'">'+value+'</span>';
                    },
                },
                type : 'TextArea',
                filters : {
                    pfiltro : 'cor.otros_adjuntos',
                    type : 'string'
                },
                id_grupo : 2,
                grid : true,
                form : true
            },
            {
                config : {
                    name : 'nro_paginas',
                    fieldLabel : 'Numero Paginas',
                    width : 200,
                    gwidth : 100
                },
                type : 'TextField',
                filters : {
                    pfiltro : 'cor.nro_paginas',
                    type : 'numeric'
                },
                id_grupo : 2,
                grid : true,
                form : true
            },
            {
                config : {
                    name : 'mensaje',
                    fieldLabel : 'Observaciones',
                    allowBlank : true,
                    width : 200,
                    growMin : 100,
                    grow : true,
                    gwidth : 100,
                    renderer : function(value, p, record) {
                        return '<span title="'+value+'">'+value+'</span>';
                    },
                },
                type : 'TextArea',
                filters : {
                    pfiltro : 'cor.mensaje',
                    type : 'string'
                },
                id_grupo : 2,
                grid : true,
                form : true
            },
            {
                config : {
                    name : 'nivel_prioridad',
                    fieldLabel : 'Nivel de Prioridad',
                    typeAhead : true,
                    allowBlank : false,
                    triggerAction : 'all',
                    selectOnFocus : true,
                    forceSelection: true,
                    disabled:true,
                    mode : 'local',
                    minChars: 2,
                    //valorInicial:{ID:'interna',valor:'Interna'},
                    store : new Ext.data.ArrayStore({
                        fields : ['ID', 'valor'],
                        data : [['1alta', 'Alta'], ['2media', 'Media'], ['3baja', 'Baja']]
                    }),
                    renderer : function(value, p, record) {
                        var prioridad = record.data.nivel_prioridad;
                        return  record.data.nivel_prioridad;
                        if (prioridad=='1alta'){
                            return 'Alta';
                        }else if (prioridad=='2media'){
                            return 'Media';
                        }else{
                            return 'Baja';
                        }
                        //return String.format('{0}', record.data['desc_clasificador']);
                    },
                    valueField : 'ID',
                    displayField : 'valor',
                    width : 300,


                },
                type : 'ComboBox',
                valorInicial : '2media',
                filters : {
                    pfiltro : 'cor.nivel_prioridad',
                    type : 'string'
                },
                id_grupo : 2,
                default:'Media',
                grid : true,
                form : true
            },
            {
                config : {
                    name : 'id_clasificador',
                    origen : 'CLASIFICADOR',
                    fieldLabel : 'Clasificación',
                    allowBlank : false,
                    gdisplayField : 'desc_clasificador', //mapea al store del grid
                    width : 200,
                    gwidth : 200,
                    renderer : function(value, p, record) {
                        return String.format('{0}', record.data['desc_clasificador']);
                    }
                },
                type : 'ComboRec',
                id_grupo : 2,
                default:'PUBLICO',
                filters : {
                    pfiltro : 'codigo#descripcion',
                    type : 'string'
                },

                grid : false,
                form : false
            },{
                config : {
                    name : 'tipo',
                    fieldLabel : 'Tipo Correspondencia',
                    typeAhead : true,
                    allowBlank : false,
                    triggerAction : 'all',
                    emptyText : 'Seleccione Opcion...',
                    selectOnFocus : true,
                    width : 200,
                    mode : 'local',
                    //disabled: true,
                    store : new Ext.data.ArrayStore({
                        fields : ['ID', 'valor'],
                        data : [['interna', 'Interna'], ['saliente', 'Saliente'], ['externa', 'Externa']],

                    }),
                    valueField : 'ID',
                    displayField : 'valor'

                },
                type : 'ComboBox',
                //valorInicial : 'interna',
                filters : {
                    pfiltro : 'cor.tipo',
                    type : 'string'
                },
                id_grupo : 0,
                grid : true,
                form : true
            }, {
                config : {
                    name : 'id_depto',
                    hiddenName : 'id_depto',
                    url : this.urlDepto,
                    origen : 'DEPTO',
                    allowBlank : false, //#6
                    fieldLabel : 'Depto Corres.', //#6
                    gdisplayField : 'desc_depto', //dibuja el campo extra de la consulta al hacer un inner join con orra tabla
                    width : 200,
                    gwidth : 180,
                    baseParams : {
                        estado : 'activo',
                        codigo_subsistema : 'CORRES'
                    }, //parametros adicionales que se le pasan al store
                    renderer : function(value, p, record) {
                        return String.format('{0}', record.data['desc_depto']);
                    }
                },
                //type:'TrigguerCombo',
                valorInicial: 'Correspondencia Oficina Central',
                type : 'ComboRec',
                id_grupo : 0,
                filters : {
                    pfiltro : 'incbte.desc_depto',
                    type : 'string'
                },
                grid : false,
                form : false
            },  {
                config : {
                    name : 'id_documento',
                    fieldLabel : 'Tipo Documento', //#6
                    allowBlank : false,
                    emptyText : 'Documento...',
                    store : new Ext.data.JsonStore({
                        url : '../../sis_parametros/control/Documento/listarDocumento',
                        id : 'id_documento',
                        root : 'datos',
                        sortInfo : {
                            field : 'codigo',
                            direction : 'ASC'
                        },
                        totalProperty : 'total',
                        fields : ['id_documento', 'codigo', 'descripcion'],
                        // turn on remote sorting
                        remoteSort : true,
                        baseParams : {
                            par_filtro : 'DOCUME.codigo#DOCUME.descripcion'
                        }
                    }),
                    valueField : 'id_documento',
                    displayField : 'descripcion',
                    gdisplayField : 'desc_documento', //mapea al store del grid
                    tpl : '<tpl for="."><div class="x-combo-list-item"><p>({codigo}) {descripcion}</p> </div></tpl>',
                    hiddenName : 'id_documento',
                    forceSelection : true,
                    typeAhead : true,
                    triggerAction : 'all',
                    lazyRender : true,
                    mode : 'remote',
                    pageSize : 10,
                    queryDelay : 1000,
                    width : 200,
                    gwidth : 150,
                    minChars : 2,
                    renderer : function(value, p, record) {
                        return String.format('{0}', record.data['desc_documento']);
                    }
                },
                type : 'ComboBox',
                id_grupo : 0,
                filters : {
                    pfiltro : 'doc.descripcion',
                    type : 'string'
                },

                grid : false,
                form : true
            },
            //#6
            /*{
                config : {
                    name : 'tipo_documento',
                    fieldLabel : 'Tipo Documento',
                    typeAhead : true,
                    allowBlank : false,
                    triggerAction : 'all',
                    emptyText : 'Seleccione Opcion...',
                    selectOnFocus : true,
                    forceSelection: true,
                    width : 300,
                    mode : 'local',

                    store : new Ext.data.ArrayStore({
                        fields : ['ID', 'valor'],
                        data : [['carta_recibida', 'Carta Recibida'],['factura', 'Factura'],['boleta_de_garantia', 'Boleta de Garantia'],['resolucion', 'Resolucion'],['acta', 'Acta']],

                    }),
                    renderer : function(value, p, record) {
                        var tipo_documento = record.data.tipo_documento;
                        return  record.data.tipo_documento;
                        if (tipo_documento=='carta_recibida'){
                            return 'Carta Recibida';
                        }else if (tipo_documento=='factura'){
                            return 'Factura';
                        }else if (tipo_documento=='resolucion'){
                            return 'Resolucion';
                        }else if (tipo_documento=='acta'){
                            return 'Acta';
                        }else{
                            return 'Boleta de Garantia';

                        }

                        //return String.format('{0}', record.data['desc_clasificador']);
                    },
                    valueField : 'ID',
                    displayField : 'valor'

                },
                type : 'ComboBox',
                valorInicial : 'carta_recibida',
                filters : {
                    pfiltro : 'cor.tipo_documento',
                    type : 'string'
                },
                id_grupo : 0,
                grid : true,
                form : true
            },*/
            {
                config : {
                    name : 'fecha_creacion_documento',
                    fieldLabel : 'Fecha Recepción',
                    allowBlank : true,
                    //anchor:'80%',
                    //format : 'd-m-Y',
                    width : 200,
                    gwidth : 100,
                    renderer : function(value, p, record) {
                        return value ? value.dateFormat('d/m/Y H:i:s'):''
                    }
                },
                type : 'DateField',
                valorInicial : new Date(),
                filters : {
                    pfiltro : 'cor.fecha_creacion_documento',
                    type : 'date'
                },
                id_grupo : 1,
                grid : true,
                form : true,
                bottom_filter : true
            },

            {
                config : {
                    name : 'origen',
                    fieldLabel : 'Origen Correspondencia',
                    gwidth : 120
                },
                type : 'TextField',
                filters : {
                    pfiltro : 'cor.origen',
                    type : 'string'
                },
                id_grupo : 1,
                grid : true,
                form : false
            },
            {
                config : {
                    name : 'id_funcionario',
                    origen : 'FUNCIONARIOCAR',
                    fieldLabel : 'Funcionario Remitente',
                    gdisplayField : 'desc_funcionario', //mapea al store del grid
                    valueField : 'id_funcionario',
                    width : 200,
                    gwidth : 200,
                    baseParams : {
                        es_combo_solicitud : 'si'
                    },
                    renderer : function(value, p, record) {
                        return String.format('{0}', record.data['desc_funcionario']);
                    }
                },
                type : 'ComboRec',
                id_grupo : 1,
                filters : {
                    pfiltro : 'desc_funcionario1',
                    type : 'string'
                },

                grid : true,
                form : true
            },
            {
                config : {
                    name : 'id_funcionario_saliente',
                    origen : 'FUNCIONARIOCAR',
                    fieldLabel : 'Funcionario Remitente Saliente',
                    allowBlank: false,
                    gdisplayField : 'desc_funcionario', //mapea al store del grid
                    valueField : 'id_funcionario',
                    width : 200,
                    gwidth : 200,
                    baseParams : {
                        es_combo_solicitud : 'si'
                    },
                    renderer : function(value, p, record) {
                        return String.format('{0}', record.data['desc_funcionario']);
                    }
                },
                type : 'ComboRec',
                id_grupo : 4,
                filters : {
                    pfiltro : 'desc_funcionario1',
                    type : 'string'
                },

                grid : true,
                form : true
            },
            {
                config:{
                    name: 'id_uo',
                    fieldLabel: 'UO Remitente',
                    allowBlank: false,
                    store : new Ext.data.JsonStore({
                        url:'../../sis_correspondencia/control/Correspondencia/obtenerUoPorFuncionario',
                        id : 'id_uo',
                        root: 'datos',
                        fields: ['id_uo','nombre_unidad','id_gerencia'],
                        remoteSort: true,
                    }),
                    readOnly:true,
                    valueField: 'id_uo',
                    displayField: 'nombre_unidad',
                    gdisplayField: 'nombre_unidad',
                    hiddenName: 'id_uo',
                    typeAhead: false,
                    triggerAction: 'all',
                    lazyRender:true,
                    mode:'remote',
                    pageSize:6,
                    queryDelay:1000,
                    anchor: '80%',
                    listWidth: 350,
                    gwidth: 150,
                    minChars:2,
                },
                type:'ComboBox',
                id_grupo:4,
                grid:true,
                form:true
            },
            {
                config : {
                    name : 'fecha_envio',
                    fieldLabel : 'Fecha Envio',
                    disabled : false,
                    allowBlank : false,
                    format : 'd/m/Y',
                    width : 200,
                    gwidth : 80,
                    renderer : function(value, p, record) {
                        return value ? value.dateFormat('d/m/Y') : ''
                    }
                },
                type : 'DateField',
                valorInicial : new Date(),
                filters : {
                    pfiltro : 'cor.fecha_envio',
                    type : 'date'
                },
                id_grupo : 4,

                grid : false,
                form : true,
                bottom_filter : true
            },
            {
                config : {
                    name : 'id_institucion_destino',
                    allowBlank : true,
                    fieldLabel : 'Institucion Destino',
                    valueField : 'id_institucion',
                    anchor : '90%',
                    tinit : true,
                    origen : 'INSTITUCION',
                    gdisplayField : 'desc_insti',
                    gwidth : 200,
                    width : 200,
                    renderer : function(value, p, record) {
                        return String.format('{0}', record.data['desc_insti']);
                    }
                },
                type : 'ComboRec',
                id_grupo : 3,
                filters : {
                    pfiltro : 'insti.nombre',
                    type : 'string'
                },
                grid : true,
                form : true
            },

            {
                config : {
                    name : 'id_persona_destino',
                    origen : 'PERSONA',
                    allowBlank : true,
                    tinit : true,
                    fieldLabel : 'Persona Destino',
                    gdisplayField : 'nombre_completo1', //mapea al store del grid
                    valueField : 'id_persona',
                    gwidth : 200,
                    renderer : function(value, p, record) {
                        return String.format('{0}', record.data['nombre_completo1']);
                    }
                },
                type : 'ComboRec',
                id_grupo : 3,
                filters : {
                    pfiltro : 'persona.nombre_completo1',
                    type : 'string'
                },

                grid : false,
                form : true,
                bottom_filter : true

            }, {
                config : {
                    name : 'persona_destino',
                    fieldLabel : 'Persona Destino',
                    gwidth : 100,
                    width : 200
                },
                type : 'TextField',
                filters : {
                    pfiltro : 'cor.persona_destino',
                    type : 'string'
                },

                id_grupo : 3,

                grid : true,
                form : true,
                bottom_filter : true
            },{
                config : {
                    name : 'id_funcionarios',
                    fieldLabel : 'Funcionario(s). Destino',
                    allowBlank : false,
                    emptyText : 'Funcionarios...',
                    store : new Ext.data.JsonStore({
                        url : '../../sis_organigrama/control/Funcionario/listarFuncionarioCargo',
                        id : 'id_funcionario',
                        root : 'datos',
                        sortInfo : {
                            field : 'desc_funcionario1',
                            direction : 'ASC'
                        },
                        totalProperty : 'total',
                        fields : ['id_funcionario', 'id_uo', 'codigo', 'nombre_cargo', 'desc_funcionario1', 'email_empresa','nombre_unidad'],
                        // turn on remote sorting
                        remoteSort : true,
                        baseParams : {
                            par_filtro : 'desc_funcionario1#email_empresa#codigo#nombre_cargo',
                            estado_reg_asi : 'activo'
                        }

                    }),
                    tpl : '<tpl for="."><div class="x-combo-list-item" ><div class="awesomecombo-item {checked}">{codigo}-{desc_funcionario1}</div><p style="padding-left: 20px;">{nombre_cargo}</p><p style="padding-left: 20px;">{nombre_unidad}</p><p style="padding-left: 20px;">{email_empresa}</p> </div></tpl>',
                    valueField : 'id_funcionario',
                    displayField : 'desc_funcionario1',
                    hiddenName : 'id_funcionarios',
                    typeAhead : true,
                    triggerAction : 'all',
                    lazyRender : true,
                    mode : 'remote',
                    pageSize : 10,
                    queryDelay : 1000,
                    width : 200,
                    minChars : 2,
                    enableMultiSelect : true
                },
                type : 'AwesomeCombo',
                id_grupo : 3,
                grid : true,
                form : true
            },

            {
                config: {
                    name: 'asociar',
                    fieldLabel: 'Asociar a?',
                    gwidth: 100,
                    items: [
                        {boxLabel: 'Interna', name: 'trg-auto', inputValue: 'interna'},
                        {boxLabel: 'Externa', name: 'trg-auto', inputValue: 'externa'},
                        {boxLabel: 'No', name: 'trg-auto', inputValue: 'No', checked: true}
                    ]
                },
                type: 'RadioGroupField',
                id_grupo: 2,
                grid: false,
                form: true
            },
            {
                config : {
                    name : 'id_correspondencias_asociadas',
                    fieldLabel : 'Responde a',
                    allowBlank : true,
                    emptyText : 'Correspondencias...',
                    store : new Ext.data.JsonStore({
                        url : '../../sis_correspondencia/control/Correspondencia/listarCorrespondenciaSimplificada',
                        id : 'id_origen',
                        root : 'datos',
                        sortInfo : {
                            field : 'id_correspondencia',
                            direction : 'desc'
                        },
                        totalProperty : 'total',
                        fields : ['id_correspondencia', 'numero', 'referencia', 'desc_funcionario','id_origen'],
                        // turn on remote sorting
                        remoteSort : true,
                        baseParams : {
                            par_filtro : 'cor.numero#cor.referencia#funcionario.desc_funcionario1#insti.nombre'
                        }
                    }),
                    tpl : '<tpl for="."><div class="x-combo-list-item" ><div class="awesomecombo-item {checked}">{numero}</div><p style="padding-left: 20px;">{referencia}</p><p style="padding-left: 20px;">{desc_funcionario}</p> </div></tpl>',
                    valueField : 'id_origen',
                    displayField : 'desc_correspondencias_asociadas',
                    gdisplayField : 'desc_correspondencias_asociadas', //mapea al store del grid

                    hiddenName : 'id_correspondencias_asociadas',
                    forceSelection : true,
                    typeAhead : true,
                    triggerAction : 'all',
                    enableMultiSelect : true,
                    lazyRender : true,
                    mode : 'remote',
                    pageSize : 10,
                    queryDelay : 1000,
                    width : 200,
                    gwidth : 200,
                    minChars : 5,
                    disabled: true,
                    renderer : function(value, p, record) {
                        return String.format('{0}', record.data['desc_correspondencias_asociadas']);
                    }
                },
                type : 'AwesomeCombo',
                id_grupo : 2,
                /*filters:{
                 pfiltro:'acco.desc_asociadas',
                 type:'string'
                 },*/

                grid : true,
                form : true
            },

            {
                config : {
                    name : 'id_acciones',
                    fieldLabel : 'Acciones',
                    allowBlank : false,
                    emptyText : 'Acciones...',
                    store : new Ext.data.JsonStore({
                        url : '../../sis_correspondencia/control/Accion/listarAccion',
                        id : 'id_accion',
                        root : 'datos',
                        sortInfo : {
                            field : 'nombre',
                            direction : 'ASC'
                        },
                        totalProperty : 'total',
                        fields : ['id_accion', 'nombre'],
                        // turn on remote sorting
                        remoteSort : true,
                        baseParams : {
                            par_filtro : 'acco.nombre'
                        }
                    }),
                    valueField : 'id_accion',
                    displayField : 'nombre',
                    gdisplayField : 'acciones', //mapea al store del grid

                    hiddenName : 'id_acciones',
                    forceSelection : true,
                    typeAhead : true,
                    triggerAction : 'all',
                    lazyRender : true,
                    mode : 'remote',
                    pageSize : 20,
                    queryDelay : 1000,
                    width : 200,
                    gwidth : 200,
                    minChars : 2,
                    enableMultiSelect : true,
                    renderer : function(value, p, record) {
                        return String.format('{0}', record.data['acciones']);
                    }
                },
                type : 'AwesomeCombo',
                id_grupo : 3,
                grid : true,
                form : true
            },
            {
                config : {
                    name : 'respuestas',
                    fieldLabel : 'respuestas',
                    allowBlank : true,
                    gwidth : 150,
                    maxLength : 500
                },
                type : 'TextField',
                filters : {
                    pfiltro : 'cor.respuestas',
                    type : 'string'
                },
                id_grupo : 1,
                grid : false,
                form : false
            },  {
                config : {
                    name : 'usr_reg',
                    fieldLabel : 'Creado por',
                    allowBlank : true,
                    gwidth : 100,
                    maxLength : 4
                },
                type : 'TextField',
                filters : {
                    pfiltro : 'usu1.cuenta',
                    type : 'string'
                },
                id_grupo : 2,
                grid : true,
                form : false
            }, {
                config : {
                    name : 'estado_reg',
                    fieldLabel : 'Estado Reg.',
                    allowBlank : false,
                    gwidth : 100,
                    maxLength : 10
                },
                type : 'TextField',
                filters : {
                    pfiltro : 'cor.estado_reg',
                    type : 'string'
                },
                id_grupo : 2,
                grid : true,
                form : false
            }, {
                config : {
                    name : 'fecha_mod',
                    fieldLabel : 'Fecha Modif.',
                    allowBlank : true,
                    gwidth : 100,
                    renderer : function(value, p, record) {
                        return value ? value.dateFormat('d/m/Y h:i:s') : ''
                    }
                },
                type : 'DateField',
                filters : {
                    pfiltro : 'cor.fecha_mod',
                    type : 'date'
                },
                id_grupo : 2,
                grid : true,
                form : false
            }, {
                config : {
                    name : 'usr_mod',
                    fieldLabel : 'Modificado por',
                    allowBlank : true,

                    gwidth : 100,
                    maxLength : 4
                },
                type : 'TextField',
                filters : {
                    pfiltro : 'usu2.cuenta',
                    type : 'string'
                },
                id_grupo : 12,
                grid : true,
                form : false
            },
            { config : {
                    name : 'id_funcionario_destino',
                    origen : 'FUNCIONARIOCAR',
                    fieldLabel : 'Funcionario Destino',
                    gdisplayField : 'desc_funcionario_origen', //mapea al store del grid
                    valueField : 'id_funcionario',
                    gwidth : 200,
                    baseParams : {
                        es_combo_solicitud : 'si'
                    },
                    renderer : function(value, p, record) {
                        return String.format('{0}', record.data['desc_funcionario_origen']);
                    }
                },
                type : 'ComboRec',
                id_grupo : 1,
                filters : {
                    pfiltro : 'emp_recepciona.desc_funcionario1',
                    type : 'string'
                },
                bottom_filter : false,
                grid : true,
                form : false
            },
            {
                config : {
                    name : 'fecha_ult_derivado',
                    fieldLabel : 'Fecha Derivado',
                    allowBlank : true,
                    anchor:'80%',
                    //format : 'd-m-Y',
                    gwidth : 100,
                    renderer : function(value, p, record) {
                        return value ? value.dateFormat('d/m/Y H:i:s'):''
                    }
                },
                type : 'DateField',
                gwidth : 100,
                //valorInicial : new Date(),
                filters : {
                    pfiltro : 'cor.fecha_ult_derivado',
                    type : 'date'
                },
                id_grupo : 2,
                grid : true,
                form : false,
                bottom_filter : false
            },{
                config : {
                    name : 'observaciones_archivado',
                    fieldLabel : 'Observaciones de Archivado',
                    width : 200,
                    growMin : 100,
                    grow : true,
                    gwidth : 400
                },
                type : 'TextArea',
                filters : {
                    pfiltro : 'cor.observaciones_archivado',
                    type : 'string'
                },
                id_grupo : 2,
                grid : true,
                form : false,
                bottom_filter : false,
                egrid : true,
                disabled : true

            },{
                config : {
                    name : 'persona_firma',
                    fieldLabel : 'Persona que Firma Carta',
                    width : 200,
                    //growMin : 100,
                    //grow : true,
                    gwidth : 400
                },
                type : 'TextField',
                filters : {
                    pfiltro : 'cor.persona_firma',
                    type : 'string'
                },
                id_grupo : 1,
                grid : false,
                form : false,
                bottom_filter : false,
                egrid : false

            },
            {
                config : {
                    name : 'fecha_documento',
                    fieldLabel : 'Fecha Documento',
                    disabled : false,
                    allowBlank : false,
                    format : 'd/m/Y',
                    width : 200,
                    gwidth : 80,
                    renderer : function(value, p, record) {
                        return value ? value.dateFormat('d/m/Y') : ''
                    }
                },
                type : 'DateField',
                valorInicial : new Date(),
                filters : {
                    pfiltro : 'cor.fecha_documento',
                    type : 'date'
                },
                id_grupo : 0,

                grid : true,
                form : true,
                bottom_filter : true
            },
            {
                config : {
                    name : 'estado',
                    fieldLabel : 'Estado',
                    allowBlank : true,
                    anchor : '80%',
                    gwidth : 100,
                    maxLength : 4
                },
                type : 'TextField',
                filters : {
                    pfiltro : 'cor.estado',
                    type : 'string'
                },
                id_grupo : 1,
                grid : true, //mgarcia
                form : false
            }


        ],
        title : 'Correspondencia',
        ActSave : '../../sis_correspondencia/control/Correspondencia/insertarCorrespondencia',
        ActDel : '../../sis_correspondencia/control/Correspondencia/eliminarCorrespondencia',
        ActList : '../../sis_correspondencia/control/Correspondencia/listarCorrespondencia',
        id_store : 'id_correspondencia',
        fields : ['id_correspondencia', 'estado', 'estado_reg', {
            name : 'fecha_documento',
            type : 'date',
            dateFormat : 'Y-m-d'
        }, {
            name : 'fecha_fin',
            type : 'date',
            dateFormat : 'Y-m-d H:i:s'
        }, 'id_acciones',

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
            {name:'mensaje', type: 'string'},
            'nivel',
            'nivel_prioridad',
            'numero',
            'observaciones_estado',
            'referencia',
            'respuestas',
            'sw_responsable',
            'tipo',
            {name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
            'id_usuario_reg',
            {name : 'fecha_mod',type : 'date',dateFormat : 'Y-m-d H:i:s'},
            'id_usuario_mod',
            'usr_reg',
            'usr_mod',
            'cite',
            'desc_depto',
            'desc_documento',
            'desc_funcionario',
            'desc_persona',
            'ruta_archivo',
            'version',
            'desc_uo',
            //'id_clasificador',
            'desc_clasificador',
            'id_origen',
            'sw_archivado',
            'estado_fisico',
            'desc_insti',
            'id_institucion_remitente',
            'id_institucion_destino',
            'nro_paginas',
            'id_persona_remitente',
            'id_persona_destino',
            'id_persona',
            'nombre_completo1',
            'otros_adjuntos',
            'adjunto','origen',
            {name:'acciones', type: 'string'},
            {name:'fecha_creacion_documento', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
            {name:'fecha_ult_derivado', type: 'date',dateFormat:'Y-m-d H:i:s.u'},'desc_funcionario_origen',
            {name:'observaciones_archivado', type: 'string'},
            {name:'sw_archivado', type: 'string'},
            {name:'desc_correspondencias_asociadas', type: 'string'},
            {name:'estado_corre', type: 'string'},
            {name:'tipo_documento', type: 'string'},
            {name:'persona_firma', type: 'string'},
            'estado_fisico',
            'persona_remitente', //#4
            'persona_destino',   //#4
            'nombre_uo_centro',
            'fisico' //#7
        ],
        arrayDefaultColumHidden:['estado','nivel_prioridad','estado','tipo','fecha_creacion_documento','origen','estado_reg','fecha_mod','usr_mod','id_uo'],

        sortInfo : {
            field : 'id_correspondencia',
            direction : 'desc'
        },
        Grupos : [{
            layout : 'column',
            border : false,
            defaults : {
                border : false
            },
            items : [{
                bodyStyle : 'padding-left:5px;padding-left:5px;',
                border : false,
                defaults : {
                    border : false
                },
                width : 600,
                items : [{
                    bodyStyle : 'padding-left:5px;padding-left:5px;',
                    items : [{
                        xtype : 'fieldset',
                        title : 'Datos Generales',
                        //autoHeight: true,
                        items : [],
                        id_grupo : 0
                    }]
                }, {
                    bodyStyle : 'padding-left:5px;padding-left:5px;',
                    items : [{
                        xtype : 'fieldset',
                        title : 'Datos Remitente',

                        // autoHeight: true,
                        items : [],
                        id_grupo : 1
                    }]
                }, {
                    bodyStyle : 'padding-left:5px;padding-left:5px;',
                    items : [{
                        xtype : 'fieldset',
                        title : 'Mensaje',
                        //autoHeight: true,
                        items : [],
                        id_grupo : 2
                    }]
                }
                    , {
                        bodyStyle : 'padding-left:5px;padding-left:5px;',
                        items : [{
                            xtype : 'fieldset',

                            title : 'Datos Destinatario',
                            // autoHeight: true,
                            items : [],
                            id_grupo : 3
                        }]
                    },
                    {
                        bodyStyle : 'padding-left:5px;padding-left:5px;',
                        items : [{
                            xtype : 'fieldset',
                            title : 'Datos Remitente Saliente',

                            // autoHeight: true,
                            items : [],
                            id_grupo : 4
                        }]
                    }]
            }]
        }],

        loadValoresIniciales : function() {

            Phx.vista.Correspondencia.superclass.loadValoresIniciales.call(this);

        },
        //0
        Bfactura: function(){
            var rec = this.sm.getSelected();
            Phx.CP.loadingShow();
            Ext.Ajax.request({
                url: '../../sis_correspondencia/control/Correspondencia/impFactura',
                params: { 'id_correspondencia': rec.data.id_correspondencia },
                success : this.successExport,
                failure: this.conexionFailure,
                timeout: this.timeout,
                scope: this
            });
        },
        //1
        BPlantilla:function(){

            var rec = this.sm.getSelected();

            Ext.Ajax.request({
                url: '../../sis_correspondencia/control/Correspondencia/PlantillaCorrespondencia',
                params: {
                    id_correspondencia: rec.data.id_correspondencia,
                    start:0,limit:1
                },
                success: this.successPlantillaCorrespondencia,
                failure: this.conexionFailure,
                timeout: this.timeout,
                scope: this
            });

        },
        //2
        BFinalizarExterna:function () {
            var rec = this.sm.getSelected();
            Phx.CP.loadingShow();
            Ext.Ajax.request({
                url: '../../sis_correspondencia/control/Correspondencia/finalizarRecepcionExterna',
                params: {
                    id_correspondencia: rec.data.id_correspondencia,
                    estado: 'recibido'
                },
                success: this.successDerivar,
                failure: this.conexionFailure,
                timeout: this.timeout,
                scope: this
            });

        },
        //3
        BSubirDocumento: function () {
            var rec = this.sm.getSelected();
            Phx.CP.loadWindows('../../../sis_correspondencia/vista/correspondencia/subirCorrespondencia.php',
                'Subir Correspondencia',
                {
                    modal: true,
                    width: 500,
                    height: 250
                }, rec.data, this.idContenedor, 'subirCorrespondencia')
        },

        //4
        BAdjuntos : function() {
            var rec = this.sm.getSelected();

            Phx.CP.loadWindows('../../../sis_correspondencia/vista/adjunto/Adjunto.php?estado='+rec.data.estado, 'Adjuntos', {
                width : 900,
                height : 400
            }, rec.data, this.idContenedor, 'Adjunto')

        },
        //5
        BCorregir : function() {


            var rec = this.sm.getSelected();
            var id_correspondencia = this.sm.getSelected().data.id_correspondencia;
            Phx.CP.loadingShow();

            Ext.Ajax.request({
                url : '../../sis_correspondencia/control/Correspondencia/corregirCorrespondencia',
                params : {
                    id_correspondencia : id_correspondencia,

                    interfaz:'normal',

                    tipo:rec.data.tipo
                },
                success : this.successDerivar,
                failure : this.conexionFailure,
                timeout : this.timeout,
                scope : this
            });

        },
        //6
        BVerDocumento : function() {
            var rec = this.sm.getSelected();
            console.log('rec', 'ingresa aqui');

            Ext.Ajax.request({
                // form:this.form.getForm().getEl(),
                url : '../../sis_correspondencia/control/Correspondencia/verCorrespondencia',
                params : {
                    id_origen : rec.data.id_origen
                },
                success : this.successVer,
                failure : this.conexionFailure,
                timeout : this.timeout,
                scope : this
            });

        },
        //7
        BImpCodigo : function () {
            var rec = this.sm.getSelected();
            Phx.CP.loadingShow();
            Ext.Ajax.request({
                url: '../../sis_correspondencia/control/Correspondencia/impCodigoCorrespondecia',
                params: { 'id_correspondencia': rec.data.id_correspondencia },
                success : this.successExport,
                failure: this.conexionFailure,
                timeout: this.timeout,
                scope: this
            });
        },
        //8
        BImpCodigoDoc: function(){
            var rec = this.sm.getSelected();
            Phx.CP.loadingShow();
            Ext.Ajax.request({
                url: '../../sis_correspondencia/control/Correspondencia/impCodigoCorrespondecia2',
                params: { 'id_correspondencia': rec.data.id_correspondencia },
                success : this.successExport,
                failure: this.conexionFailure,
                timeout: this.timeout,
                scope: this
            });
        },
        //9
        BDerivar : function() {

            var rec = this.sm.getSelected();
            var id_correspondencia = this.sm.getSelected().data.id_correspondencia;
            if (confirm('¿Esta seguro de DERIVAR el documento '+rec.data.numero + '?, no podrá modificar información al respecto.')){
                Phx.CP.loadingShow();
                Ext.Ajax.request({
                    url : '../../sis_correspondencia/control/Correspondencia/derivarCorrespondencia',
                    params : {
                        id_correspondencia : id_correspondencia,
                        id_origen : rec.data.id_origen
                    },
                    success : this.successDerivar,
                    failure : this.conexionFailure,
                    timeout : this.timeout,
                    scope : this
                });
            }
        },
        //10
        BHojaRuta : function() {

            var rec = this.sm.getSelected();
            Ext.Ajax.request({
                url : '../../sis_correspondencia/control/Correspondencia/hojaRuta',
                params : {
                    id_correspondencia : rec.data.id_correspondencia,
                    id_origen: rec.data.id_origen,
                    tipo_corres:rec.data.tipo,
                    estado_reporte:'finalizado',
                    id_institucion : rec.data.id_institucion,//mgarcia
                    start : 0,
                    limit : 1
                },
                success : this.successHojaRuta,
                failure : this.conexionFailure,
                timeout : this.timeout,
                scope : this
            });

        },

        /* 11 histórico.
         * hoja pop up para la hoja de ruta
         */
        BHistorico : function() {
            var rec = this.sm.getSelected();
            var remitente;
            if (rec.data.desc_insti==null){
                remitente=rec.data.nombre_completo1;
            }else{
                remitente=rec.data.desc_insti;
            }

            Phx.CP.loadWindows('../../../sis_correspondencia/vista/correspondencia/Historico.php',remitente+' -- '+rec.data.numero, {
                width : 900,
                height : 400
            }, rec.data, this.idContenedor, 'Historico')
        },
        BFinalizar:function () {
            var rec = this.sm.getSelected();
            var g_estado;
            if (confirm('Esta seguro de FINALIZAR el documento '+rec.data.numero)){

                Phx.CP.loadingShow();
                //alert(rec.data.tipo);
                switch (rec.data.tipo){
                    case 'externa':
                        g_estado='pendiente_recepcion_externo';
                        break;
                    case 'saliente':
                        g_estado='enviado';
                        break;
                    case 'interna':
                        g_estado='recibido';
                        break;
                }
                Ext.Ajax.request({
                    url: '../../sis_correspondencia/control/Correspondencia/finalizarRecepcionExterna',

                    params: {
                        id_correspondencia: rec.data.id_correspondencia,
                        estado:g_estado

                    },
                    success: this.successDerivar,
                    failure: this.conexionFailure,
                    timeout: this.timeout,
                    scope: this
                });
            }

        },
        BArchivar:function(){
            var rec = this.sm.getSelected();
            console.log('manu',rec.data.fisico);
            if(rec.data.fisico=='si'){
                if(confirm('Esta seguro de Archivar el documento '+rec.data.numero+'?')){
                    var result = prompt('Especifique la ubicación física del documento y las razones por las que se archiva el Documento'+rec.data.numero);
                    if (result != null){
                        Ext.Ajax.request({
                            url: '../../sis_correspondencia/control/Correspondencia/archivarCorrespondencia',
                            params: {
                                id_correspondencia: rec.data.id_correspondencia,
                                sw_archivado :'si',
                                observaciones_archivado:result
                            },
                            success: this.successFinalizar,
                            failure: this.conexionFailure,
                            timeout: this.timeout,
                            scope: this
                        });
                    }
                }
            }else{
                alert('Esta accion solo puede realizar el que tenga el archivo fisico');
            }
        },
        BHabilitar:function(){
            var rec = this.sm.getSelected();
            if (confirm('Esta seguro de habilitar el anulado?'+rec.data.numero)){

                Ext.Ajax.request({
                    url: '../../sis_correspondencia/control/Correspondencia/habilitarCorrespondencia',
                    params: {
                        id_correspondencia: rec.data.id_correspondencia,
                        tipo_corres:rec.data.tipo
                    },
                    success: this.successFinalizar,
                    failure: this.conexionFailure,
                    timeout: this.timeout,
                    scope: this
                });
            }
        },
        successPlantillaCorrespondencia:function(resp){

            var objRes = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));

            var texto = objRes.datos.docx;
            console.log(texto);
            window.open('../../../lib/lib_control/Intermediario.php?r=' + texto + '&t=' + new Date().toLocaleTimeString())


        },

        successDerivar : function(resp) {

            Phx.CP.loadingHide();
            var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
            if (!reg.ROOT.error) {
                alert(reg.ROOT.detalle.mensaje)

            }
            this.reload();

        },

        successVer : function(resp) {

            var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
            console.log(reg.datos[0].ruta_archivo);
            window.open(reg.datos[0].ruta_archivo);
        },


        successGestion : function(resp) {
            console.log(resp)
            Phx.CP.loadingHide();
            var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
            if (!reg.ROOT.error) {
                this.cmpIdGestion.setValue(reg.ROOT.datos.id_gestion);

            } else {
                alert('ocurrio al obtener la gestion');
            }
        },

        successHojaRuta : function(resp) {
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

        successFinalizar:function(resp){

            this.load({params: {start: 0, limit: 50}});
            console.log(resp)
        },
        obtenerGestion : function(x) {

            var fecha = x.getValue().dateFormat(x.format);
            Phx.CP.loadingShow();
            Ext.Ajax.request({
                // form:this.form.getForm().getEl(),
                url : '../../sis_parametros/control/Gestion/obtenerGestionByFecha',
                params : {
                    fecha : fecha
                },
                success : this.successGestion,
                failure : this.conexionFailure,
                timeout : this.timeout,
                scope : this
            });
        }



    })
</script>
