CREATE TABLE IF NOT EXISTS csw2_vehicules (
        vehicule_id INT NOT NULL AUTO_INCREMENT,
        vehicule_marque VARCHAR(255) NOT NULL,
        vehicule_modele VARCHAR(255) NOT NULL,
        vehicule_couleur VARCHAR(255) NOT NULL,
        vehicule_annee_circulation YEAR NOT NULL,
        vehicule_kilometrage INT NOT NULL,
        vehicule_prix INT NOT NULL,
        vehicule_date_enregistrement TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        vehicule_visibilite VARCHAR(255) NOT NULL,
        vehicule_proprietaire_id INT NOT NULL,
        PRIMARY KEY (vehicule_id))
    ENGINE = InnoDB;



INSERT INTO csw2_vehicules(
    vehicule_marque,
    vehicule_modele,
    vehicule_couleur,
    vehicule_annee_circulation,
    vehicule_kilometrage,
    vehicule_prix,
    vehicule_proprietaire_id)        
VALUES
    ('Toyota', 'TOYCRX', 'rouge', 2003, 3000, 12000, 1, 1),
    ('Prius', 'PRICRX', 'verte', 2005, 12000, 10000, 1, 0),
    ('Ionic', 'IONCXCS', 'noir', 2002, 30000, 8000, 1, 1),
    ('Toyota','TOYCRX', 'rouge', 2008, 150000, 12000, 1, 2),
    ('Prius', 'PRICRX', 'verte', 1996, 300000, 10000, 1, 3),
    ('Ionic', 'IONCXCS', 'noir', 2012, 20000, 8000, 0, 2),
    ('Toyota', 'TOYCRX', 'rouge', 2015, 32000, 12000, 1, 3),
    ('Prius', 'PRICRX', 'verte', 2001, 10000, 10000, 1, 1),
    ('Ionic', 'IONCXCS', 'noir', 1998, 200000, 8000, 1, 2),
    ('Toyota', 'TOYCRX', 'rouge', 2006, 120000, 12000, 0, 3);




