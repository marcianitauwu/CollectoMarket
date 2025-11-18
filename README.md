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
## Paleta de colores
- Azul Principal: #3498DB (Un azul vibrante y amigable para elementos principales y llamadas a la acción)

- Gris Neutro: #ECF0F1 (Un gris claro para fondos y secciones de contenido, que proporciona un buen contraste)

- Gris Oscuro (Texto): #34495E (Un gris oscuro para el texto principal, que es legible y sofisticado)

- Verde Acento: #2ECC71 (Un verde brillante para indicar éxito, disponibilidad o elementos destacados)

- Naranja Acento: #E67E22 (Un naranja cálido para alertas, ofertas especiales o elementos secundarios importantes)
