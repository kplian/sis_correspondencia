<<?php
/**
 * @package pxP
 * @file 	GridReporteCorrespondencia.php
 * @author 	JMH
 * @date	21/11/2018
 * @description	Reporte Correspondencia
 */
header("content-type:text/javascript; charset=UTF-8");
?>
<script>
	Phx.vista.GridReporteCorrespondencia = Ext.extend(Phx.gridInterfaz, {
		constructor : function(config) {
			this.maestro = config;
			this.uo = this.desc_uo;
		
			Phx.vista.GridReporteCorrespondencia.superclass.constructor.call(this, config);
			this.init();
			//this.store.baseParams.id_usuario = this.maestro.id_usuario;
			this.load({
				params : {
					start: 0,
					limit: this.tam_pag,
					fecha_ini:this.maestro.fecha_ini,
					fecha_fin:this.maestro.fecha_fin,
					id_uo:this.maestro.id_uo,
                    id_usuario: this.maestro.id_usuario,
                    tipo: this.maestro.tipo,
                    id_documento:this.maestro.id_documento,
                    estado: this.maestro.estado
				}
			});
		},
		tam_pag:1000,
		Atributos : [
            {
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
                    name: 'estado',
                    fieldLabel: 'Estado',
                    gwidth: 90
                },
                type: 'Field',
                filters: {
                    pfiltro: 'f.sigla',
                    type:'string'
                },
                grid: true

            },

            {
                config: {
                    name: 'fecha_documento',
                    fieldLabel: 'Fecha Doc.',
                    gwidth: 110,
                    renderer : function(value, p, record) {
					return value ? value.dateFormat('d/m/Y'):''
				}
                },
                type: 'DateField',
                filters: {
                    pfiltro: 'cor.fecha_documento',
                    type:'date'
                },
                grid: true

            },
            {
                config: {
                    name: 'mensaje',
                    fieldLabel: 'Mensaje',
                    gwidth: 110
                },
                type: 'Field',
                filters: {
                    pfiltro: 'cor.mensaje',
                    type:'string'
                },
                grid: true

            },
            {
                config: {
                    name: 'nivel_prioridad',
                    fieldLabel: 'nivel_prioridad',
                    gwidth: 110
                },
                type: 'Field',
                filters: {
                    pfiltro: 'cor.nivel_prioridad',
                    type:'string'
                },
                grid: true

            },
            {
                config: {
                    name: 'numero',
                    fieldLabel: 'Número',
                    gwidth: 110
                },
                type: 'Field',
                filters: {
                    pfiltro: 'cor.numero',
                    type:'string'
                },
                grid: true

            },
            {
                config: {
                    name: 'observaciones_estado',
                    fieldLabel: ' observaciones_estado',
                    gwidth: 110
                },
                type: 'Field',
                filters: {
                    pfiltro: 'cor.observaciones_estado',
                    type:'string'
                },
                grid: true

            },
            {
                config: {
                    name: 'referencia',
                    fieldLabel: 'Referencia',
                    gwidth: 110
                },
                type: 'Field',
                grid: true

            },
            {
                config: {
                    name: 'usr_reg',
                    fieldLabel: 'Usuario Registro',
                    gwidth: 110
                },
                type: 'Field',
                grid: true

            },
            {
                config: {
                    name: 'usr_mod',
                    fieldLabel: 'Usuario Modificador',
                    gwidth: 110
                },
                type: 'Field',
                grid: true

            },
            {
                config: {
                    name: 'desc_funcionario',
                    fieldLabel: 'Funcionario',
                    gwidth: 110
                },
                type: 'Field',
                grid: true

            },
            {
                config: {
                    name: 'version',
                    fieldLabel: 'Súbidas Documento',
                    gwidth: 110
                },
                type: 'Field',
                grid: true

            },
            {
                config: {
                    name: 'desc_clasificador',
                    fieldLabel: 'Clasificador',
                    gwidth: 110
                },
                type: 'Field',
                grid: true

            },
            {
                config: {
                    name: 'desc_cargo',
                    fieldLabel: 'Cargo',
                    gwidth: 110
                },
                type: 'Field',
                grid: true

            },
            {
                config: {
                    name: 'sw_archivado',
                    fieldLabel: 'Archivado?',
                    gwidth: 110
                },
                type: 'Field',
                grid: true

            },
            {
                config: {
                    name: 'desc_insti',
                    fieldLabel: 'Institución',
                    gwidth: 110
                },
                type: 'Field',
                grid: true

            },
            {
                config: {
                    name: 'persona_remi',
                    fieldLabel: 'Persona',
                    gwidth: 110
                },
                type: 'Field',
                grid: true

            },
             {
                config: {
                    name: 'tipo',
                    fieldLabel: 'Tipo',
                    gwidth: 110
                },
                type: 'Field',
                grid: true

            },
            {
                config: {
                    name: 'documento',
                    fieldLabel: 'Documento',
                    gwidth: 110
                },
                type: 'TextField',
                grid: true

            },
             {
                config: {
                    name: 'id_documento',
                    fieldLabel: 'id_documento',
                    gwidth: 110
                },
                type: 'Field',
                grid: false

            },
            {
                config: {
                    name: 'acciones',
                    fieldLabel: 'Acción',
                    gwidth: 110
                },
                type: 'TextField',
                grid: false

            }
			
		],
		title : 'Reporte Correspondencia',
		ActList : '../../sis_correspondencia/control/Reporte/listarReporteCorrespondencia',
		id_store : 'id_correspondencia',
		fields : [{
			name : 'id_correspondencia'
		}, {
			name : 'estado',
			type : 'string'
		} ,{
			name : 'fecha_documento',
			type : 'date',
			dateFormat : 'Y-m-d'
		},{
            name : 'mensaje',
            type : 'string'
        },{
            name : 'nivel_prioridad',
            type : 'string'
        },{
            name : 'numero',
            type : 'string'
        },{
            name : 'observaciones_estado',
            type : 'string'
        },{
            name : 'referencia',
            type : 'string'
        },{
            name : 'usr_reg',
            type : 'string'
        },{
            name : 'usr_mod',
            type : 'string'
        },{
            name : 'desc_funcionario',
            type : 'string'
        }, {
			name : 'version',
			type : 'numeric'
		},
        {
            name : 'desc_clasificador',
            type : 'string'
        },
        {
            name : 'desc_cargo',
            type : 'string'
        },
        {
            name : 'desc_insti',
            type : 'string'
        },
        {
            name : 'persona_remi',
            type : 'string'
        },
        {
            name : 'tipo',
            type : 'string'
        },
         {
            name : 'documento',
            type : 'string'
        },
        
        {
            name : 'id_documento',
            type : 'numeric'
        },
        
        {
            name : 'acciones',
            type : 'string'
        },
        {
            name : 'sw_archivado',
            type : 'string'
        }],
        arrayDefaultColumHidden:['nivel_prioridad','observaciones_estado','desc_clasificador'],
		sortInfo : {
			field : 'id_correspondencia',
			direction : 'ASC'
		},
		bdel : false,
		bnew: false,
		bedit: false,
		fwidth : '90%',
		fheight : '80%'
	}); 
</script>
