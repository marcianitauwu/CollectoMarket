<?php
require_once 'includes/auth.php'; // Requiere que el usuario esté logueado
require_once 'config/db.php';
require_once 'includes/header.php';

$message = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}
?>

<div class="form-container" style="max-width: 800px;">
    <h2>Publicar Nuevo Anuncio </h2>
    <p>Como usuario con cuenta, quiero crear un anuncio para vender uno de mis coleccionables.</p>

    <?php if ($message): ?>
        <p class="message" style="color: red; font-weight: bold;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form action="actions/publish_action.php" method="POST" enctype="multipart/form-data">
        
        <div class="form-group">
            <label for="name">Nombre del Artículo:</label>
            <input type="text" id="name" name="name" required>
        </div>

        <div class="form-group">
            <label for="description">Descripción:</label>
            <textarea id="description" name="description" rows="4" required></textarea>
        </div>

        <div class="form-group">
            <label for="price_real">Precio Estimado en Dinero Real ($):</label>
            <input type="number" id="price_real" name="price_real" step="0.01" min="0" required>
        </div>

        <div class="form-group">
            <label for="image">Imagen del Coleccionable:</label>
            <input type="file" id="image" name="image" accept="image/*" required>
        </div>

        <div class="form-group">
            <label for="type">Tipo de Coleccionable:</label>
            <select id="type" name="type" required onchange="toggleFields()">
                <option value="">-- Selecciona el tipo --</option>
                <option value="brainrot">Brainrot (Roblox)</option>
                <option value="pokemon">Carta Pokémon</option>
            </select>
        </div>

        <fieldset id="brainrot_fields" style="display: none; border: 1px solid #f7d142; padding: 20px; margin-bottom: 20px;">
            <legend>Detalles del Brainrot</legend>
            
            <div class="form-group">
                <label for="br_rarity">Rareza:</label>
                <select name="br_rarity">
                    <option value="">-- Selecciona la Rareza --</option>
                    <option value="Comun">Común</option>
                    <option value="Raro">Raro</option>
                    <option value="Epico">Épico</option>
                    <option value="Legendario">Legendario</option>
                    <option value="Mistico">Místico</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="br_color">Color:</label>
                <select name="br_color">
                    <option value="">-- Selecciona el Color --</option>
                    <option value="Rojo">Rojo</option>
                    <option value="Azul">Azul</option>
                    <option value="Verde">Verde</option>
                    <option value="Amarillo">Amarillo</option>
                    <option value="Blanco">Blanco</option>
                    <option value="Negro">Negro</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="br_profit_game">Dinero que Produce en el Juego:</label>
                <input type="number" name="br_profit_game" step="0.01" min="0">
            </div>
            
            <div class="form-group">
                <label for="br_price_game">Precio Dentro del Juego:</label>
                <input type="number" name="br_price_game" step="0.01" min="0">
            </div>
        </fieldset>

        <fieldset id="pokemon_fields" style="display: none; border: 1px solid #3b4cca; padding: 20px; margin-bottom: 20px;">
            <legend>Detalles de la Carta Pokémon</legend>
            
            <div class="form-group">
                <label for="pk_energy_type">Tipo de Energía:</label>
                <select name="pk_energy_type">
                    <option value="">-- Selecciona el Tipo --</option>
                    <option value="Fuego">Fuego</option>
                    <option value="Agua">Agua</option>
                    <option value="Planta">Planta</option>
                    <option value="Electrico">Eléctrico</option>
                    <option value="Psiquico">Psíquico</option>
                    <option value="Lucha">Lucha</option>
                    <option value="Oscuridad">Oscuridad</option>
                    <option value="Metal">Metal</option>
                    <option value="Incolora">Incolora</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="pk_rarity">Rareza:</label>
                <select name="pk_rarity">
                    <option value="">-- Selecciona la Rareza --</option>
                    <option value="Comun">Común</option>
                    <option value="Infrecuente">Infrecuente</option>
                    <option value="Rara">Rara</option>
                    <option value="Holo Rara">Holo Rara</option>
                    <option value="Secreta">Secreta</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="pk_hp">Puntos de Vida (HP):</label>
                <input type="number" name="pk_hp" min="1">
            </div>
            
            <div class="form-group">
                <label for="pk_attack">Poder de Ataque:</label>
                <input type="number" name="pk_attack" min="0">
            </div>
            
            <div class="form-group">
                <label for="pk_edition">Edición:</label>
                <select name="pk_edition">
                    <option value="">-- Selecciona la Edición --</option>
                    <option value="Base Set">Base Set</option>
                    <option value="Jungle">Jungle</option>
                    <option value="Fossil">Fossil</option>
                    <option value="Neo">Neo</option>
                    <option value="Ex">Ex Series</option>
                    <option value="Modern">Moderno (Actual)</option>
                </select>
            </div>
        </fieldset>

        <button type="submit" class="btn">Crear Publicación</button>
    </form>
</div>

<script>
    function toggleFields() {
        const type = document.getElementById('type').value;
        const brainrotFields = document.getElementById('brainrot_fields');
        const pokemonFields = document.getElementById('pokemon_fields');

        // Resetear la visibilidad
        brainrotFields.style.display = 'none';
        pokemonFields.style.display = 'none';

        // Mostrar el conjunto de campos correspondiente
        if (type === 'brainrot') {
            brainrotFields.style.display = 'block';
        } else if (type === 'pokemon') {
            pokemonFields.style.display = 'block';
        }
    }
    // Ejecutar al cargar la página para mantener el estado si hay errores
    document.addEventListener('DOMContentLoaded', toggleFields);
</script>

<?php
require_once 'includes/footer.php';
?>