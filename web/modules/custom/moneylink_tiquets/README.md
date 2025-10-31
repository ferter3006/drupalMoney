# MoneyLink Tiquets

Módulo para gestionar la visualización y creación de tickets en el sistema MoneyLink.

## Características

### Formulario de Creación de Tickets
- Ruta: `/ml/tiquets/create/{sala_id}`
- Formulario estilizado con validación en tiempo real
- Integración con las APIs de MoneyLink (categorías y tickets)
- Manejo automático de logout cuando el token expira

### Funcionalidades Implementadas
- ✅ Formulario de creación de tickets
- ✅ Template personalizado con estilos modernos
- ✅ Validación del lado cliente y servidor
- ✅ Integración con servicios de API existentes
- ✅ Manejo de errores y redirecciones automáticas

### Funcionalidades Planificadas
- ⏳ Lista de tickets del usuario (`/ml/tiquets`)
- ⏳ Vista individual de tickets (`/ml/tiquets/{tiquet_id}`)
- ⏳ Edición de tickets
- ⏳ Filtros y búsqueda

## Estructura de Archivos

```
moneylink_tiquets/
├── moneylink_tiquets.info.yml
├── moneylink_tiquets.module
├── moneylink_tiquets.routing.yml
├── moneylink_tiquets.services.yml
├── moneylink_tiquets.libraries.yml
├── src/
│   ├── Controller/
│   │   └── TiquetsController.php
│   ├── Form/
│   │   └── CreateTiquetForm.php
│   └── Service/
│       └── TiquetsFormHelper.php
├── templates/
│   └── moneylink-tiquets-create-form.html.twig
├── css/
│   └── create-form.css
├── js/
│   └── create-form.js
└── README.md
```

## Dependencias

- `moneylink_auth` - Para autenticación
- `moneylink_store` - Para almacenamiento de datos
- `moneylink_apitiquets` - Para servicios de API de tickets
- `moneylink_apicategorias` - Para servicios de API de categorías

## Uso

### Crear un Ticket
1. Navegar a `/ml/tiquets/create/{sala_id}` donde `{sala_id}` es el ID de la sala
2. Completar el formulario:
   - Seleccionar categoría
   - Elegir tipo de movimiento (Ingreso/Gasto)
   - Ingresar cantidad
   - Escribir descripción
3. Hacer clic en "Crear Tiquet"

### Características del Formulario
- **Validación en tiempo real**: Los campos se validan mientras el usuario escribe
- **Contador de caracteres**: Para la descripción (mínimo 10 caracteres)
- **Diseño responsivo**: Se adapta a dispositivos móviles
- **Animaciones suaves**: Para mejorar la experiencia de usuario

## Integración con APIs

El módulo utiliza los servicios existentes de `moneylink_apitiquets` y `moneylink_apicategorias` para:
- Obtener categorías disponibles
- Crear nuevos tickets
- Manejar autenticación y logout automático