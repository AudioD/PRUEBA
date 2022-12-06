USE hospital;

INSERT INTO `departamentos` (`id`, `nombre`) VALUES
(1, 'Amazonas'),
(2, 'Antioquia'),
(3, 'Bolívar'),
(4, 'Boyacá'),
(5, 'Caldas');

INSERT INTO `genero` (`id`, `nombre`) VALUES
(1, 'Hombre'),
(2, 'Mujer'),
(3, 'No binario');

INSERT INTO `municipios` (`id`, `departamento_id`, `nombre`) VALUES
(1, 1, 'Leticia'),
(2, 1, 'Puerto Arica '),
(3, 1, 'Puerto Nariño '),
(4, 2, 'Abejorral'),
(5, 2, 'Medellín'),
(6, 2, 'Argelia'),
(7, 3, 'Arenal'),
(8, 3, 'Córdoba'),
(9, 3, 'Margarita'),
(10, 4, 'Tunja'),
(11, 4, 'Ciénega'),
(12, 4, 'Chiquinquirá'),
(13, 5, 'Risaralda'),
(14, 5, 'Marquetalia'),
(15, 5, 'Belalcázar');

INSERT INTO `tipos_documento` (`id`, `nombre`) VALUES
(1, 'Cedula'),
(2, 'Cedula de extranjeria');

INSERT INTO `paciente` (`tipo_documento_id`, `numero_documento`, `nombre1`, `nombre2`, `apellido1`, `apellido2`, `genero_id`, `departamento_id`, `municipio_id`, `imagen`) VALUES
(1, 123, 'Jorge', '', 'Amaranto', 'Sucre', 1, 4, 11, '202212061439481386694184-3718555412.png'),
(1, 456, 'Juana', 'Andrea', 'Bedolla', '', 2, 5, 13, '20221206144103ramayanam-lord-3970138107.jpg'),
(2, 678, 'Fabian', 'Ramirez', 'Perez', 'Perez', 3, 1, 3, '20221206144250budgerigar_imp-230726200.jpg'),
(1, 98, 'Fernando', '', 'de Jesus', '', 1, 2, 4, '20221206144408th-1602421330.jpg'),
(1, 123456, 'Luz', 'Mery', 'Giraldo', 'Atheortua', 2, 3, 7, '202212061447279526949-3582399063.png');

INSERT INTO `usuarios` (`id`, `nombre`, `correo`, `documento`, `tipo_documento_id`, `password`) VALUES
(2, 'administrador', 'admin@hospital.com', 901234567, 1, '1234567890');