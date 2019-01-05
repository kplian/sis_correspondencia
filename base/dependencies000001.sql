/***********************************I-DEP-FRH-CORRES-0-24/01/2013*****************************************/

ALTER TABLE ONLY corres.tcorrespondencia
    ADD CONSTRAINT fk_tcorrespondencia__id_funcionario
    FOREIGN KEY (id_funcionario) REFERENCES orga.tfuncionario(id_funcionario);

 
ALTER TABLE ONLY corres.tcorrespondencia
    ADD CONSTRAINT fk_tcorrespondencia__id_correspondencia
    FOREIGN KEY (id_correspondencia_fk) REFERENCES corres.tcorrespondencia(id_correspondencia);

 
ALTER TABLE ONLY corres.tcorrespondencia
    ADD CONSTRAINT fk_tcorrespondencia__id_persona
    FOREIGN KEY (id_persona) REFERENCES segu.tpersona(id_persona);

 
ALTER TABLE ONLY corres.tcorrespondencia
    ADD CONSTRAINT fk_tcorrespondencia__id_codumento
    FOREIGN KEY (id_documento) REFERENCES param.tdocumento(id_documento);

 
ALTER TABLE ONLY corres.tcorrespondencia
    ADD CONSTRAINT fk_tcorrespondencia__id_uo
    FOREIGN KEY (id_uo) REFERENCES orga.tuo(id_uo);

 
ALTER TABLE ONLY corres.tcorrespondencia
    ADD CONSTRAINT fk_tcorrespondencia__id_depto
    FOREIGN KEY (id_depto) REFERENCES param.tdepto(id_depto);

 
ALTER TABLE ONLY corres.tcorrespondencia
    ADD CONSTRAINT fk_tcorrespondencia__id_usuario
    FOREIGN KEY (id_usuario_reg) REFERENCES segu.tusuario(id_usuario);

 
ALTER TABLE ONLY corres.tcorrespondencia_estado
    ADD CONSTRAINT fk_tcorrespondencia_estado__id_correspondencia
    FOREIGN KEY (id_correspondencia) REFERENCES corres.tcorrespondencia(id_correspondencia);

 
ALTER TABLE ONLY corres.tcorrespondencia_estado
    ADD CONSTRAINT fk_tcorrespondencia_estado__id_usuario_reg
    FOREIGN KEY (id_usuario_reg) REFERENCES segu.tusuario(id_usuario);

 
ALTER TABLE ONLY corres.tcomision
    ADD CONSTRAINT fk_tcomision__id_usuario
    FOREIGN KEY (id_usuario_reg) REFERENCES segu.tusuario(id_usuario);

 
ALTER TABLE ONLY corres.tcomision
    ADD CONSTRAINT fk_tcomision__id_funcionario
    FOREIGN KEY (id_funcionario) REFERENCES orga.tfuncionario(id_funcionario);

 
ALTER TABLE ONLY corres.tcomision
    ADD CONSTRAINT fk_tcomision__id_correspondencia
    FOREIGN KEY (id_correspondencia) REFERENCES corres.tcorrespondencia(id_correspondencia);

 
ALTER TABLE ONLY corres.tcorrespondencia
    ADD CONSTRAINT fk_tcorrespondencia__id_gestion
    FOREIGN KEY (id_gestion) REFERENCES param.tgestion(id_gestion);

 
ALTER TABLE ONLY corres.tcorrespondencia
    ADD CONSTRAINT fk_tcorrespondencia__id_periodo
    FOREIGN KEY (id_periodo) REFERENCES param.tperiodo(id_periodo);


ALTER TABLE ONLY corres.tgrupo
    ADD CONSTRAINT tgrupo__id_usuario_fk
    FOREIGN KEY (id_usuario_reg) REFERENCES segu.tusuario(id_usuario);

 
ALTER TABLE ONLY corres.tgrupo
    ADD CONSTRAINT tgrupo__is_usuario_mod_fk
    FOREIGN KEY (id_usuario_mod) REFERENCES segu.tusuario(id_usuario);


 
ALTER TABLE ONLY corres.tgrupo_funcionario
    ADD CONSTRAINT tgrupo_funcionario_id_usuario_reg_fk
    FOREIGN KEY (id_usuario_reg) REFERENCES segu.tusuario(id_usuario);

 
ALTER TABLE ONLY corres.tgrupo_funcionario
    ADD CONSTRAINT tgrupo_funcionario__id_usuaio_mod_fk
    FOREIGN KEY (id_usuario_mod) REFERENCES segu.tusuario(id_usuario);

 
ALTER TABLE ONLY corres.tgrupo_funcionario
    ADD CONSTRAINT tgrupo_funcionario__id_grupo_fk
    FOREIGN KEY (id_grupo) REFERENCES corres.tgrupo(id_grupo);

 
ALTER TABLE ONLY corres.tgrupo_funcionario
    ADD CONSTRAINT tgrupo_funcionario__if_funcionario_fk
    FOREIGN KEY (id_funcionario) REFERENCES orga.tfuncionario(id_funcionario);

 
ALTER TABLE ONLY corres.tcorrespondencia
    ADD CONSTRAINT fk_tcorrespondencia__id_clasificacor
    FOREIGN KEY (id_clasificador) REFERENCES segu.tclasificador(id_clasificador);
    

CREATE TRIGGER trig_correspondencia_estado
  AFTER INSERT OR UPDATE 
  ON corres.tcorrespondencia FOR EACH ROW 
  EXECUTE PROCEDURE corres.trig_correspondencia();

/***********************************F-DEP-FRH-CORRES-0-24/01/2013*****************************************/


/***********************************I-DEP-FFP-CORRES-0-26/04/2016*****************************************/


CREATE OR REPLACE VIEW corres.vcorrespondencia_fisica_emitida AS
				select
				(CASE WHEN (cor.id_correspondencia is not null) then
					(
						SELECT  pxp.list(corfk.id_origen::VARCHAR)
						FROM corres.tcorrespondencia corfk
						WHERE corfk.id_correspondencia_fk = cor.id_correspondencia
									and corfk.id_depto != cor.id_depto)
				 END ) as tiene,
				cor.id_correspondencia,
				cor.numero,
				cor.fecha_documento,
					cor.ruta_archivo,
					    cor.id_depto,
					    cor.estado_fisico


			from corres.tcorrespondencia cor;





/***********************************F-DEP-FFP-CORRES-0-26/04/2016*****************************************/

/***********************************I-DEP-FPC-CORRES-0-11/10/2017*****************************************/
ALTER TABLE corres.tadjunto
  ADD CONSTRAINT tadjunto_fk FOREIGN KEY (id_correspondencia_origen)
    REFERENCES corres.tcorrespondencia(id_correspondencia)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;
    
 /***********************************F-DEP-FPC-CORRES-0-11/10/2017*****************************************/



/***********************************I-DEP-JMH-CORRES-0-12/12/2018*****************************************/
select pxp.f_insert_testructura_gui ('CORCONF', 'CORRES');
select pxp.f_insert_testructura_gui ('BANCOR', 'CORRES');
select pxp.f_insert_testructura_gui ('COREXTE', 'CORRES');
select pxp.f_insert_testructura_gui ('DOCFISCA', 'CORRES');
select pxp.f_insert_testructura_gui ('ACCCOR', 'CORCONF');
select pxp.f_insert_testructura_gui ('GRUPCOR', 'CORCONF');
select pxp.f_insert_testructura_gui ('CEMITIDA', 'BANCOR');
select pxp.f_insert_testructura_gui ('CRECI', 'BANCOR');
select pxp.f_insert_testructura_gui ('COREAR', 'BANCOR');
select pxp.f_insert_testructura_gui ('RECEPEXTE', 'COREXTE');
select pxp.f_insert_testructura_gui ('DEVCOREX', 'COREXTE');
select pxp.f_insert_testructura_gui ('DESPCH', 'DOCFISCA');
select pxp.f_insert_testructura_gui ('DOCFISREC', 'DOCFISCA');
select pxp.f_insert_testructura_gui ('CORFISEM', 'DOCFISCA');
select pxp.f_insert_testructura_gui ('GRUPCOR.1', 'GRUPCOR');
select pxp.f_insert_testructura_gui ('GRUPCOR.1.1', 'GRUPCOR.1');
select pxp.f_insert_testructura_gui ('GRUPCOR.1.1.1', 'GRUPCOR.1.1');
select pxp.f_insert_testructura_gui ('GRUPCOR.1.1.2', 'GRUPCOR.1.1');
select pxp.f_insert_testructura_gui ('GRUPCOR.1.1.3', 'GRUPCOR.1.1');
select pxp.f_insert_testructura_gui ('GRUPCOR.1.1.1.1', 'GRUPCOR.1.1.1');
select pxp.f_insert_testructura_gui ('GRUPCOR.1.1.1.1.1', 'GRUPCOR.1.1.1.1');
select pxp.f_insert_testructura_gui ('GRUPCOR.1.1.1.1.1.1', 'GRUPCOR.1.1.1.1.1');
select pxp.f_insert_testructura_gui ('GRUPCOR.1.1.1.1.1.2', 'GRUPCOR.1.1.1.1.1');
select pxp.f_insert_testructura_gui ('GRUPCOR.1.1.1.1.1.2.1', 'GRUPCOR.1.1.1.1.1.2');
select pxp.f_insert_testructura_gui ('GRUPCOR.1.1.1.1.1.2.2', 'GRUPCOR.1.1.1.1.1.2');
select pxp.f_insert_testructura_gui ('CEMITIDA.1', 'CEMITIDA');
select pxp.f_insert_testructura_gui ('CEMITIDA.2', 'CEMITIDA');
select pxp.f_insert_testructura_gui ('CEMITIDA.3', 'CEMITIDA');
select pxp.f_insert_testructura_gui ('CEMITIDA.4', 'CEMITIDA');
select pxp.f_insert_testructura_gui ('CEMITIDA.5', 'CEMITIDA');
select pxp.f_insert_testructura_gui ('CEMITIDA.2.1', 'CEMITIDA.2');
select pxp.f_insert_testructura_gui ('CEMITIDA.2.2', 'CEMITIDA.2');
select pxp.f_insert_testructura_gui ('CEMITIDA.2.1.1', 'CEMITIDA.2.1');
select pxp.f_insert_testructura_gui ('CEMITIDA.2.1.2', 'CEMITIDA.2.1');
select pxp.f_insert_testructura_gui ('CEMITIDA.2.1.2.1', 'CEMITIDA.2.1.2');
select pxp.f_insert_testructura_gui ('CEMITIDA.2.1.2.2', 'CEMITIDA.2.1.2');
select pxp.f_insert_testructura_gui ('CEMITIDA.2.2.1', 'CEMITIDA.2.2');
select pxp.f_insert_testructura_gui ('CRECI.1', 'CRECI');
select pxp.f_insert_testructura_gui ('CRECI.2', 'CRECI');
select pxp.f_insert_testructura_gui ('CRECI.3', 'CRECI');
select pxp.f_insert_testructura_gui ('CRECI.4', 'CRECI');
select pxp.f_insert_testructura_gui ('CRECI.1.1', 'CRECI.1');
select pxp.f_insert_testructura_gui ('CRECI.1.2', 'CRECI.1');
select pxp.f_insert_testructura_gui ('CRECI.1.1.1', 'CRECI.1.1');
select pxp.f_insert_testructura_gui ('CRECI.1.1.2', 'CRECI.1.1');
select pxp.f_insert_testructura_gui ('CRECI.1.1.2.1', 'CRECI.1.1.2');
select pxp.f_insert_testructura_gui ('CRECI.1.1.2.2', 'CRECI.1.1.2');
select pxp.f_insert_testructura_gui ('CRECI.1.2.1', 'CRECI.1.2');
select pxp.f_insert_testructura_gui ('COREAR.1', 'COREAR');
select pxp.f_insert_testructura_gui ('COREAR.2', 'COREAR');
select pxp.f_insert_testructura_gui ('COREAR.3', 'COREAR');
select pxp.f_insert_testructura_gui ('COREAR.4', 'COREAR');
select pxp.f_insert_testructura_gui ('COREAR.1.1', 'COREAR.1');
select pxp.f_insert_testructura_gui ('COREAR.1.2', 'COREAR.1');
select pxp.f_insert_testructura_gui ('COREAR.1.1.1', 'COREAR.1.1');
select pxp.f_insert_testructura_gui ('COREAR.1.1.2', 'COREAR.1.1');
select pxp.f_insert_testructura_gui ('COREAR.1.1.2.1', 'COREAR.1.1.2');
select pxp.f_insert_testructura_gui ('COREAR.1.1.2.2', 'COREAR.1.1.2');
select pxp.f_insert_testructura_gui ('COREAR.1.2.1', 'COREAR.1.2');
select pxp.f_insert_testructura_gui ('RECEPEXTE.1', 'RECEPEXTE');
select pxp.f_insert_testructura_gui ('RECEPEXTE.2', 'RECEPEXTE');
select pxp.f_insert_testructura_gui ('RECEPEXTE.3', 'RECEPEXTE');
select pxp.f_insert_testructura_gui ('RECEPEXTE.4', 'RECEPEXTE');
select pxp.f_insert_testructura_gui ('RECEPEXTE.5', 'RECEPEXTE');
select pxp.f_insert_testructura_gui ('RECEPEXTE.2.1', 'RECEPEXTE.2');
select pxp.f_insert_testructura_gui ('RECEPEXTE.2.2', 'RECEPEXTE.2');
select pxp.f_insert_testructura_gui ('RECEPEXTE.2.1.1', 'RECEPEXTE.2.1');
select pxp.f_insert_testructura_gui ('RECEPEXTE.2.1.2', 'RECEPEXTE.2.1');
select pxp.f_insert_testructura_gui ('RECEPEXTE.2.1.2.1', 'RECEPEXTE.2.1.2');
select pxp.f_insert_testructura_gui ('RECEPEXTE.2.1.2.2', 'RECEPEXTE.2.1.2');
select pxp.f_insert_testructura_gui ('RECEPEXTE.2.2.1', 'RECEPEXTE.2.2');
select pxp.f_insert_testructura_gui ('DEVCOREX.1', 'DEVCOREX');
select pxp.f_insert_testructura_gui ('DEVCOREX.2', 'DEVCOREX');
select pxp.f_insert_testructura_gui ('DEVCOREX.3', 'DEVCOREX');
select pxp.f_insert_testructura_gui ('DEVCOREX.4', 'DEVCOREX');
select pxp.f_insert_testructura_gui ('DEVCOREX.5', 'DEVCOREX');
select pxp.f_insert_testructura_gui ('DEVCOREX.2.1', 'DEVCOREX.2');
select pxp.f_insert_testructura_gui ('DEVCOREX.2.2', 'DEVCOREX.2');
select pxp.f_insert_testructura_gui ('DEVCOREX.2.1.1', 'DEVCOREX.2.1');
select pxp.f_insert_testructura_gui ('DEVCOREX.2.1.2', 'DEVCOREX.2.1');
select pxp.f_insert_testructura_gui ('DEVCOREX.2.1.2.1', 'DEVCOREX.2.1.2');
select pxp.f_insert_testructura_gui ('DEVCOREX.2.1.2.2', 'DEVCOREX.2.1.2');
select pxp.f_insert_testructura_gui ('DEVCOREX.2.2.1', 'DEVCOREX.2.2');
select pxp.f_insert_testructura_gui ('CORFISEM.1', 'CORFISEM');
select pxp.f_insert_testructura_gui ('CORFISEM.2', 'CORFISEM');
select pxp.f_insert_testructura_gui ('CORFISEM.3', 'CORFISEM');
select pxp.f_insert_testructura_gui ('CORFISEM.4', 'CORFISEM');
select pxp.f_insert_testructura_gui ('CORFISEM.1.1', 'CORFISEM.1');
select pxp.f_insert_testructura_gui ('CORFISEM.1.2', 'CORFISEM.1');
select pxp.f_insert_testructura_gui ('CORFISEM.1.1.1', 'CORFISEM.1.1');
select pxp.f_insert_testructura_gui ('CORFISEM.1.1.2', 'CORFISEM.1.1');
select pxp.f_insert_testructura_gui ('CORFISEM.1.1.2.1', 'CORFISEM.1.1.2');
select pxp.f_insert_testructura_gui ('CORFISEM.1.1.2.2', 'CORFISEM.1.1.2');
select pxp.f_insert_testructura_gui ('CORFISEM.1.2.1', 'CORFISEM.1.2');
select pxp.f_insert_testructura_gui ('GRUPCOR.1.1.4', 'GRUPCOR.1.1');
select pxp.f_insert_testructura_gui ('GRUPCOR.1.1.1.1.2', 'GRUPCOR.1.1.1.1');
select pxp.f_insert_testructura_gui ('GRUPCOR.1.1.1.1.2.1', 'GRUPCOR.1.1.1.1.2');
select pxp.f_insert_testructura_gui ('GRUPCOR.1.1.1.1.2.1.1', 'GRUPCOR.1.1.1.1.2.1');
select pxp.f_insert_testructura_gui ('GRUPCOR.1.1.1.1.2.1.2', 'GRUPCOR.1.1.1.1.2.1');
select pxp.f_insert_testructura_gui ('GRUPCOR.1.1.1.1.2.1.2.1', 'GRUPCOR.1.1.1.1.2.1.2');
select pxp.f_insert_testructura_gui ('GRUPCOR.1.1.1.1.2.1.2.2', 'GRUPCOR.1.1.1.1.2.1.2');
select pxp.f_insert_testructura_gui ('CEMITIDA.2.2.2', 'CEMITIDA.2.2');
select pxp.f_insert_testructura_gui ('CEMITIDA.2.2.2.1', 'CEMITIDA.2.2.2');
select pxp.f_insert_testructura_gui ('CRECI.1.2.2', 'CRECI.1.2');
select pxp.f_insert_testructura_gui ('CRECI.1.2.2.1', 'CRECI.1.2.2');
select pxp.f_insert_testructura_gui ('COREAR.1.2.2', 'COREAR.1.2');
select pxp.f_insert_testructura_gui ('COREAR.1.2.2.1', 'COREAR.1.2.2');
select pxp.f_insert_testructura_gui ('RECEPEXTE.2.2.2', 'RECEPEXTE.2.2');
select pxp.f_insert_testructura_gui ('RECEPEXTE.2.2.2.1', 'RECEPEXTE.2.2.2');
select pxp.f_insert_testructura_gui ('DEVCOREX.2.2.2', 'DEVCOREX.2.2');
select pxp.f_insert_testructura_gui ('DEVCOREX.2.2.2.1', 'DEVCOREX.2.2.2');
select pxp.f_insert_testructura_gui ('CORFISEM.1.2.2', 'CORFISEM.1.2');
select pxp.f_insert_testructura_gui ('CORFISEM.1.2.2.1', 'CORFISEM.1.2.2');
select pxp.f_delete_testructura_gui ('CS', 'CORRES');
select pxp.f_insert_testructura_gui ('EMEXTE', 'BANCOR');
select pxp.f_insert_testructura_gui ('CORARC', 'CORRES');
select pxp.f_insert_testructura_gui ('CORARCH', 'CORARC');
select pxp.f_insert_testructura_gui ('COREXT', 'COREXTE');
select pxp.f_insert_testructura_gui ('CORADM', 'CORRES');
select pxp.f_insert_testructura_gui ('CORADMG', 'CORADM');
select pxp.f_insert_testructura_gui ('ADMCORINT', 'CORADM');
select pxp.f_insert_testructura_gui ('REPCOR', 'CORRES');
select pxp.f_insert_testructura_gui ('RECODT', 'REPCOR');

/***********************************F-DEP-JMH-CORRES-0-26/12/2018*****************************************/
/***********************************I-DEP-AVQ-CORRES-0-27/12/2018*****************************************/
ALTER TABLE corres.tcorrespondencia ADD COLUMN estado_corre  VARCHAR(30);
/***********************************F-DEP-AVQ-CORRES-0-27/12/2018*****************************************/
/***********************************I-DEP-AVQ-CORRES-0-28/12/2018*****************************************/
update segu.tgui
set parametros= '{"tipo":"interna"}'
where codigo_gui='CRECI';

update segu.tgui
set parametros= '{"tipo":"externa"}'
where codigo_gui='RECEPEXTE';

update segu.tgui
set parametros= '{"tipo":"externa"}'
where codigo_gui='DEVCOREX';

update segu.tgui
set parametros= '{"tipo":"externa"}'
where codigo_gui='COREXT';

update segu.tgui
set parametros= '{"tipo":"externa","estado":"borrador_recepcion_externo"}'
where codigo_gui='CORADMG';

update segu.tgui
set parametros= '{"tipo":"interna","estado":"borrador_envio"}'
where codigo_gui='ADMCORINT';

/***********************************F-DEP-AVQ-CORRES-0-28/12/2018*****************************************/
/***********************************I-DEP-AVQ-CORRES-0-31/12/2018*****************************************/
ALTER TABLE corres.tcorrespondencia
  ADD COLUMN tipo_documento VARCHAR(100);

ALTER TABLE corres.tcorrespondencia
  ALTER COLUMN tipo_documento SET DEFAULT 'otros';

ALTER TABLE corres.tcorrespondencia
  ADD COLUMN persona_firma VARCHAR(100);
/***********************************F-DEP-AVQ-CORRES-0-31/12/2018*****************************************/
