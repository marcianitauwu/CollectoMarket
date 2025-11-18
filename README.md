# MarketFlow
## Estructura del proyecto
/marketflow-project
│
├── /config             # Configuración de la base de datos
│   └── db.php          # Conexión a MySQL (PDO)
│
├── /models             # Clases del Diagrama (Usuario, Producto, Chat, etc.)
│   ├── Usuario.php
│   ├── Administrador.php
│   ├── Producto.php
│   ├── Chat.php
│   ├── Mensaje.php
│   ├── Categoria.php
│   └── Imagen.php
│
├── /public             # Archivos estáticos y punto de entrada
│   ├── /css            # Estilos (si no usas Tailwind CDN)
│   ├── /js             # Scripts
│   ├── /uploads        # Fotos de productos
│   └── index.php       # Podría ser un enrutador simple, o cada archivo .php se llama directamente
│
├── /panels             # ¡Aquí irán tus archivos .PHP de cada panel!
    ├── login.php           # Panel Login
    ├── register.php        # Panel Crear Cuenta
    ├── catalog.php         # Panel Principal (Catálogo de productos)
    ├── my-products.php     # Panel Mis Publicaciones
    ├── create-product.php  # Panel Nueva Publicación
    ├── chat.php            # Panel de Chat
    └── product-detail.php  # (Para la vista de un producto individual, si es necesaria)

