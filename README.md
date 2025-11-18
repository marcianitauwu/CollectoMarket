# MarketFlow
## Estructura del proyecto
```
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
│   ├── /assets         # Imágenes de placeholder, logos, etc.
│   └── index.php       # Podría ser un enrutador simple, o redirigir
│
├── /includes           # Aquí ponemos los archivos reutilizables (Header, Sidebar)
│   ├── header.php      # Contiene la barra superior
│   └── sidebar.php     # Contiene el menú lateral
│
├── /panels             # Aquí irán tus archivos .PHP de cada panel
    ├── login.php           # Panel Login (sin header/sidebar completo, ya que es una página distinta)
    ├── register.php        # Panel Crear Cuenta (sin header/sidebar completo)
    ├── catalog.php         # Panel Principal (Catálogo de productos)
    ├── my-products.php     # Panel Mis Publicaciones
    ├── create-product.php  # Panel Nueva Publicación
    ├── chat.php            # Panel de Chat
    └── product-detail.php  # Panel de Detalles del Producto

```

