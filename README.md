# Prueba Técnica - MSI Group
**Postulante:** Octavio
**Posición:** Programador PHP/Laravel

## Descripción
Resolución de los puntos 3 y 4 de la evaluación técnica para el sistema de gestión de reservas de restaurante.

## Tecnologías Utilizadas
* PHP Puro (POO)
* MySQL (PDO con Prepared Statements)
* Bootstrap 5 (Maquetado responsivo)

## Instalación
1. Importar el archivo `sql/database.sql` en MySQL.
2. Configurar credenciales en `config/db_config.php`.
3. Ejecutar en servidor local (XAMPP/Laragon).

## Decisiones Técnicas
* **Punto 3:** Se implementó una lógica de validación de horarios basada en la consigna y un algoritmo de unión de hasta 3 mesas por ubicación.
* **Punto 4:** Se optimizó la consulta SQL utilizando `GROUP_CONCAT` para reducir la carga de datos y mejorar la visualización.

Desde ya, muchas gracias.
Saludos, Octavio Valenzuela.