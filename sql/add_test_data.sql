-- Lisää INSERT INTO lauseet tähän tiedostoon
INSERT INTO Registered (name) VALUES ('Pekka');
INSERT INTO Registered (name) VALUES ('Timo');

INSERT INTO Eventgroup (name, description) VALUES ('Testiryhmä', 'Tätä ryhmää käytetään testaamiseen.');

INSERT INTO Membership (registered_id, eventgroup_id) VALUES ((SELECT id FROM Registered WHERE name = 'Pekka'), (SELECT id FROM Eventgroup WHERE name = 'Testiryhmä'));

INSERT INTO Event (eventday, eventtime, description, registered_id, eventgroup_id) VALUES ((SELECT CURRENT_DATE), (SELECT LOCALTIME), 'Testausta', (SELECT id FROM Registered WHERE name = 'Pekka'), (SELECT id FROM Eventgroup WHERE name = 'Testiryhmä'));
INSERT INTO Event (eventday, eventtime, description, registered_id, eventgroup_id) VALUES ((SELECT CURRENT_DATE), (SELECT LOCALTIME), 'Testauksen testausta', (SELECT id FROM Registered WHERE name = 'Timo'), NULL); 
