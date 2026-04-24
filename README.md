# Sistema de Impresión para Restaurantes

Sistema completo de impresión remota para restaurantes usando Laravel + Electron + WebSocket.

## 🏗️ Arquitectura

```
App Web (Vercel/Web) → Servidor (realdev.cl) → PC Local (Restaurante)
                           ↓                        ↓
                    notifier-server           Laravel + Electron
                    (WebSocket Server)        (Cliente de Impresión)
```

## 📁 Estructura del Proyecto

- **/** - Proyecto Laravel (cliente de impresión)
- **app.notifier/** - App Electron (interfaz desktop)
- **notifier-server/** - Servidor WebSocket Node.js

## 🚀 Instalación en PC del Restaurante

### Requisitos
- Windows 10+
- XAMPP ([descargar](https://www.apachefriends.org/download.html))
- Composer ([descargar](https://getcomposer.org/download/))
- Node.js 14+ ([descargar](https://nodejs.org/))

### Paso 1: Instalar XAMPP
1. Descargar e instalar XAMPP
2. Iniciar Apache desde el panel de control de XAMPP

### Paso 2: Clonar el proyecto en XAMPP
```bash
# Clonar en la carpeta notifier dentro de htdocs
git clone https://github.com/koki198977/notifier.git C:\xampp\htdocs\notifier
cd C:\xampp\htdocs\notifier
```

### Paso 3: Configurar Laravel
```bash
# Instalar dependencias PHP
composer install

# Configurar entorno
cp .env.example .env

# Generar clave de aplicación
php artisan key:generate
```

### Paso 4: Configurar comercio
Editar `.env` y cambiar:
```env
LARAVEL_COD_COMERCIO=TU_CODIGO_COMERCIO
```

### Paso 5: Configurar XAMPP para servir Laravel directamente
Para que `localhost` vaya directo a Laravel, necesitas modificar la configuración de Apache en XAMPP.

**Método recomendado: Cambiar DocumentRoot en httpd.conf**

1. Abre el archivo: `C:\xampp\apache\conf\httpd.conf`
2. Busca la línea que dice `DocumentRoot` (alrededor de la línea 251)
3. Cámbiala por:
```apache
DocumentRoot "C:/xampp/htdocs/notifier/public"
```
4. Busca la línea `<Directory` correspondiente y cámbiala por:
```apache
<Directory "C:/xampp/htdocs/notifier/public">
```
5. Guarda el archivo y reinicia Apache desde el panel de XAMPP

**Método alternativo: Redirección con .htaccess**
Si prefieres no modificar `httpd.conf`, crea el archivo `C:\xampp\htdocs\.htaccess`:
```apache
RewriteEngine On
RewriteRule ^$ notifier/public/ [R=301,L]
```

**Resultado**: `localhost` apuntará directamente a Laravel sin mostrar la página de XAMPP.

### Paso 6: Configurar App Electron
```bash
cd app.notifier
npm install
npm run dist  # Genera el ejecutable
```

## 🖥️ Ejecución

### Producción (Recomendado)
1. **Iniciar Apache** desde XAMPP
2. **Ejecutar**: `app.notifier/dist/server Setup 1.0.0.exe`

**Nota**: XAMPP está configurado para servir directamente desde `/notifier/public/` modificando el `DocumentRoot` en `httpd.conf`.

### Desarrollo (Alternativo)
Si necesitas hacer cambios al código:
```bash
# Opción 1: Usar XAMPP (recomendado)
# Solo inicia Apache y accede a localhost

# Opción 2: Laravel independiente (solo para desarrollo)
php artisan serve --port=8000
# Luego ejecutar app Electron (cambiará la URL a localhost:8000)
```

**Nota**: En producción NO necesitas terminales abiertos. Apache sirve Laravel automáticamente a través de `localhost` → `/notifier/public/`.

## 📡 Uso desde tu App Web

```javascript
// Enviar orden de impresión
await fetch('https://realdev.cl/api/solicita_ticket', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    comercio: 'TU_CODIGO_COMERCIO',
    impresora: 'NOMBRE_IMPRESORA',
    mesa: '5',
    movimiento: '12345',
    mesero: 'Juan',
    detalle: [
      { nombre: 'Pizza Margherita', cantidad: 1, precio: 15000 }
    ]
  })
});
```

## 🖨️ Endpoints Disponibles

- `POST /api/pre_cuenta` - Pre-cuenta con delivery
- `POST /api/solicita_ticket` - Ticket de cocina
- `POST /api/solicita_happy` - Ticket happy hour
- `POST /api/solicita_boleta_electronica` - Boleta electrónica SII

## ⚙️ Configuración por Comercio

Cada comercio necesita:
1. **Código único**: `LARAVEL_COD_COMERCIO=CODIGO123`
2. **Nombre de impresora**: En el JSON de la petición
3. **Instalación local**: Este proyecto + app Electron

## 🔧 Desarrollo

### Servidor WebSocket (notifier-server)
```bash
cd notifier-server
npm install
node index.js
```

### Compilar assets
```bash
npm run dev    # Desarrollo
npm run prod   # Producción
```

## 📝 Notas Importantes

- El servidor WebSocket debe estar corriendo en `realdev.cl:6001`
- Cada PC necesita tener el proyecto Laravel corriendo en `localhost:8000`
- La app Electron se conecta automáticamente al WebSocket
- Los nombres de impresora deben coincidir exactamente con Windows

## 🆘 Solución de Problemas

### `localhost` muestra página de XAMPP en lugar de Laravel
- Verificar que modificaste `C:\xampp\apache\conf\httpd.conf` correctamente
- Asegúrate de que el `DocumentRoot` apunte a `"C:/xampp/htdocs/notifier/public"`
- Reinicia Apache desde el panel de XAMPP después de cambiar `httpd.conf`
- Alternativamente, usa el método `.htaccess` si no quieres modificar `httpd.conf`

### App Electron no conecta
- Verificar que Laravel esté accesible en `localhost` (no `localhost:8000`)
- Revisar que `realdev.cl:6001` esté accesible
- Comprobar que Apache esté corriendo en XAMPP

### No imprime
- Verificar nombre de impresora en Windows
- Comprobar que la impresora esté encendida y conectada
- Revisar logs en la app Electron

### Error de conexión WebSocket
- Verificar conectividad a `realdev.cl:6001`
- Comprobar firewall/antivirus

### Laravel muestra errores
- Verificar que `composer install` se ejecutó correctamente
- Comprobar que el archivo `.env` tiene `LARAVEL_COD_COMERCIO` configurado
- Verificar permisos de carpetas `storage/` y `bootstrap/cache/`

## 📄 Licencia

MIT License
