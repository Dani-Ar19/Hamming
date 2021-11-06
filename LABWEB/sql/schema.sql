CREATE TABLE public.usuario (
	id serial NOT NULL,
	nombre varchar NULL,
	apellido varchar NULL,
	nick varchar NOT NULL,
	email varchar NULL,
	direccion varchar NULL,
	telefono varchar NULL,
	genero varchar NULL,
	CONSTRAINT usuario_pk PRIMARY KEY (id),
	CONSTRAINT usuario_un UNIQUE (nick)
);
