-- Lisää CREATE TABLE lauseet tähän tiedostoon

CREATE TABLE Registered(
  id SERIAL PRIMARY KEY,
  name varchar(50) NOT NULL,
  password_digest varchar(255) NOT NULL
);

CREATE TABLE Eventgroup(
  id SERIAL PRIMARY KEY,
  name varchar(50) NOT NULL,
  description varchar(200)
);

CREATE TABLE Membership(
  registered_id INTEGER REFERENCES Registered(id),
  eventgroup_id INTEGER REFERENCES Eventgroup(id) ON DELETE CASCADE,
  CONSTRAINT Membership_pkey PRIMARY KEY (registered_id, eventgroup_id)
);

CREATE TABLE Event(
  id SERIAL PRIMARY KEY,
  eventday DATE NOT NULL,
  eventtime TIME NOT NULL,
  description varchar(200) NOT NULL,
  registered_id INTEGER REFERENCES Registered(id) NOT NULL,
  eventgroup_id INTEGER REFERENCES Eventgroup(id)
);
