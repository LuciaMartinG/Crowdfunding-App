# 🌟 STEMFounding – Full-Stack Web Application

---

## 🇬🇧 English

A comprehensive web platform connecting **entrepreneurs** and **investors**. Entrepreneurs can create and manage projects, while investors can support them via micro-financing. Developed during my web programming studies.


## 🛠️ Technologies Used

### Frontend
[![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)](https://developer.mozilla.org/es/docs/Web/HTML) [![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)](https://developer.mozilla.org/es/docs/Web/CSS) [![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)](https://developer.mozilla.org/es/docs/Web/JavaScript) [![Blade](https://img.shields.io/badge/Blade-Laravel-orange?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com/docs/9.x/blade)
 [![React Native](https://img.shields.io/badge/React_Native-20232A?style=for-the-badge&logo=react&logoColor=61DAFB)](https://reactnative.dev/) [![Bootstrap](https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com/)

### Backend
[![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/) [![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com/)

### Databases
[![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)

### Tools & Others
[![Docker](https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white)](https://www.docker.com/) [![Composer](https://img.shields.io/badge/Composer-999999?style=for-the-badge&logo=composer&logoColor=white)](https://getcomposer.org/) [![Git](https://img.shields.io/badge/Git-F05032?style=for-the-badge&logo=git&logoColor=white)](https://git-scm.com/) [![API REST](https://img.shields.io/badge/API_REST-FF6F61_)]()

### ✨ Features

- **Authentication & User Roles:** Entrepreneurs, Investors, Admins. Role-based authentication, password recovery, and profile management.
- **Project Management:** Create/manage projects, progress bars, update logs, max 2 active projects per entrepreneur.
- **Investment Module:** Search/filter projects, minimum investment 1€, automatic refunds, withdraw within 24h.
- **Administration:** User management, project approval/rejection, manual deactivation.
- **REST API:** Endpoints for projects, token-based authentication.
- **Frontend & UI:** Initial responsive UI developed with Laravel Blade + Bootstrap. Later, a mobile version was developed in the `STEMFounding_React` folder using React Native for entrepreneurs.


---

### 🏗 Getting Started

#### Prerequisites

- [Docker](https://www.docker.com/products/docker-desktop)
- [Docker Compose](https://docs.docker.com/compose/)

#### Installation

```bash
git clone https://github.com/yourusername/PROYECTO-FINAL.git
cd PROYECTO-FINAL/STEMFounding
cp .env.example .env
docker-compose up --build
```

**Useful Commands:**

```bash
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
```


### 🔒 Security Notes

- The `.env` file is **not included** in the repository. Use `.env.example` to configure your local environment.
- This project is for demonstration purposes only and does not contain real data or sensitive credentials.


### 🚀 Demo

Open in your local environment: [http://localhost:8000](http://localhost:8000)

**Demo Users:**

| Role         | Email                    | Password |
|--------------|--------------------------|----------|
| Admin        | admin@example.com        | 1234     |
| Entrepreneur | entrepreneur@example.com | 1234     |
| Investor     | investor@example.com     | 1234     |

> Update these credentials according to your seeders.

---

### 📫 Contact

Feel free to reach out or connect with me:

- ✉️  [Send me an email](mailto:lmguijarro92@gmail.com) 

- <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/linkedin.svg" alt="LinkedIn" width="20"/> [Visit my LinkedIn](https://www.linkedin.com/in/lucia-martin-guijarro)  

---

## 🇪🇸 Español

STEMFounding es plataforma web integral que conecta **emprendedores** e **inversores**. Los emprendedores pueden crear y gestionar proyectos, mientras que los inversores pueden apoyarlos mediante microfinanciación. Desarrollado durante mis estudios de programación web.


### ✨ Funcionalidades

- **Autenticación y roles:** Emprendedores, Inversores, Administradores. Recuperación de contraseña y gestión de perfil.
- **Gestión de proyectos:** Crear/gestionar proyectos, barras de progreso, logs de avance, máximo 2 activos por emprendedor.
- **Módulo de inversión:** Buscar/filtrar proyectos, inversión mínima 1€, reembolsos automáticos, retiro en 24h.
- **Administración:** Gestión de usuarios, aprobación/rechazo de proyectos, desactivación manual.
- **API REST:** Endpoints para proyectos, autenticación por token.
- **Frontend & UI:** Interfaz responsive inicial desarrollada con Laravel Blade + Bootstrap. Además, se creó una versión móvil en la carpeta `STEMFounding_React` utilizando React Native para emprendedores.



### 🛠 Tecnologías

- **Backend:** Laravel 11, PHP 8.2
- **Base de datos:** MySQL 8 (Docker)
- **Frontend:** Laravel Blade & React (API)
- **Control de versiones:** Git (main / development / feature/*)
- **Contenedores:** Docker & Docker Compose
- **Gestión de dependencias:** Composer



### 🏗 Puesta en marcha

#### Prerrequisitos

- [Docker](https://www.docker.com/products/docker-desktop)
- [Docker Compose](https://docs.docker.com/compose/)

#### Instalación

```bash
git clone https://github.com/yourusername/PROYECTO-FINAL.git
cd PROYECTO-FINAL/STEMFounding
cp .env.example .env
docker-compose up --build
```

**Comandos útiles:**

```bash
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
```


### 🔒 Notas de seguridad

- El archivo `.env` **no está incluido** en el repositorio. Usa `.env.example` para configurar tu entorno local.
- Este proyecto es solo para fines demostrativos y no contiene datos reales ni credenciales sensibles.



### 🚀 Demo

Abre en tu entorno local: [http://localhost:8000](http://localhost:8000)

**Usuarios de prueba:**

| Rol          | Email                    | Contraseña |
|--------------|--------------------------|------------|
| Admin        | admin@example.com        | 1234       |
| Emprendedor  | entrepreneur@example.com | 1234       |
| Inversor     | investor@example.com     | 1234       |

> Si modificas los seeders, actualiza estas credenciales.

---

### 📫 Contacto

- ✉️  [Envíame un email](mailto:lmguijarro92@gmail.com) 

- <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/linkedin.svg" alt="LinkedIn" width="20"/> [Visita mi LinkedIn](https://www.linkedin.com/in/lucia-martin-guijarro)  




