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
- PHP 7.4+ ([descargar](https://windows.php.net/download/))
- Composer ([descargar](https://getcomposer.org/download/))
- Node.js 14+ ([descargar](https://nodejs.org/))

### Paso 1: Clonar el proyecto
```bash
git clone https://github.com/koki198977/notifier.git
cd notifier
```

### Paso 2: Configurar Laravel
```bash
# Instalar dependencias PHP
composer install

# Configurar entorno
cp .env.example .env

# Generar clave de aplicación
php artisan key:generate
```

### Paso 3: Configurar comercio
Editar `.env` y cambiar:
```env
LARAVEL_COD_COMERCIO=TU_CODIGO_COMERCIO
```

### Paso 4: Configurar App Electron
```bash
cd app.notifier
npm install
npm run dist  # Genera el ejecutable
```

## 🖥️ Ejecución

### Opción 1: Desarrollo
```bash
# Terminal 1: Laravel
php artisan serve --port=8000

# Terminal 2: Electron
cd app.notifier
npm start
```

### Opción 2: Producción
1. Ejecutar: `app.notifier/dist/server Setup 1.0.0.exe`
2. En otra terminal: `php artisan serve --port=8000`

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

### App Electron no conecta
- Verificar que Laravel esté en `localhost:8000`
- Revisar que `realdev.cl:6001` esté accesible

### No imprime
- Verificar nombre de impresora en Windows
- Comprobar que la impresora esté encendida y conectada
- Revisar logs en la app Electron

### Error de conexión WebSocket
- Verificar conectividad a `realdev.cl:6001`
- Comprobar firewall/antivirus

## 📄 Licencia

MIT License
