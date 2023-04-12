LOAD DATA INFILE 'equipe.10.csv' INTO TABLE acte_deces
FIELDS TERMINATED BY ';'
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(agent_saisie_id,numero_acte,full_name,date_deces,lieu_deces,age,date_naissance,lieu_naissance,profession,domicile,declarant,date_saisie,slug,date_acte,centre_etat_civil)
