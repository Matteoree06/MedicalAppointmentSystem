# MedicalAppointmentSystem
Proyecto para Arquitectura de Software GRUPO 2 ESPE

# MANUAL DE INSTALACIÓN, CLONACIÓN Y EJECUCIÓN DEL PROYECTO

## **1. Clonar el proyecto desde GitHub**

Abrir una terminal en la carpeta donde se desea guardar el proyecto y ejecuta:

```bash
[git clone https://github.com/usuario/nombre-proyecto.git](https://github.com/Matteoree06/MedicalAppointmentSystem.git)
```

Luego entra al directorio del proyecto:

```bash
cd MedicalAppointmentSystem
```

---

## **2. Instalar dependencias del proyecto**

### a) Dependencias de PHP (usando Composer):

```bash
composer install
```

### b) Dependencias del Front-end (usando NPM):

```bash
npm install
```

## **3. Configurar el archivo `.env`**

Copiar el archivo de ejemplo `.env.example` y renómbrarlo como `.env`:

```bash
cp .env.example .env
```

Luego editamos con las credenciales locales de base de datos, por ejemplo:

```env
APP_NAME="SistemaCitasMedicas"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=citas_medicas
DB_USERNAME=jairo2002
DB_PASSWORD=1234ab
```

## **4. Generar la clave de aplicación**

Ejecutamos en una terminal con:

```bash
php artisan key:generate
```

Esto actualizará automáticamente la variable `APP_KEY` del archivo `.env`.

---

## **5. Ejecutar las migraciones y seeders**

Esto creará todas las tablas y poblará la base con datos de prueba:

Para las migraciones

```bash
php artisan migrate
```

Para los seeder

```bash
php artisan db:seed --class=MedicalAppointmentSeeder
```

