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
    FOREIGN KEY (id_depto) REFERENCES orga.tdepto(id_depto);

 
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

/***********************************F-DEP-FRH-CORRES-0-24/01/2013*****************************************/
