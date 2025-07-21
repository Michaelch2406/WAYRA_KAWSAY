<?php
// Modals para el panel de administración optimizado
?>

<!-- Modal Gestión de Usuarios -->
<div class="modal fade" id="modalUsuario" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalUsuarioTitle">Crear Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formUsuario">
                    <input type="hidden" id="usuario_id" name="id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="usuario_nombre" class="form-label">Nombre *</label>
                                <input type="text" class="form-control" id="usuario_nombre" name="nombre" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="usuario_email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="usuario_email" name="email" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="usuario_telefono" class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" id="usuario_telefono" name="telefono">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="usuario_rol" class="form-label">Rol *</label>
                                <select class="form-select" id="usuario_rol" name="rol" required>
                                    <option value="">Seleccionar rol</option>
                                    <option value="admin">Administrador</option>
                                    <option value="artesano">Artesano</option>
                                    <option value="comunitario">Comunitario</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="usuario_password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="usuario_password" name="password">
                        <div class="form-text">Dejar vacío para mantener la contraseña actual (solo en edición)</div>
                    </div>
                    
                    <!-- Campos específicos para artesano -->
                    <div id="campos_artesano" style="display: none;">
                        <hr>
                        <h6>Información de Artesano</h6>
                        <div class="mb-3">
                            <label for="artesano_historia" class="form-label">Historia</label>
                            <textarea class="form-control" id="artesano_historia" name="historia" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="artesano_especialidad" class="form-label">Especialidad</label>
                                    <input type="text" class="form-control" id="artesano_especialidad" name="especialidad">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="artesano_telefono" class="form-label">Teléfono de Contacto</label>
                                    <input type="tel" class="form-control" id="artesano_telefono" name="telefono_contacto">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="artesano_foto" class="form-label">Foto de Perfil</label>
                            <input type="file" class="form-control" id="artesano_foto" name="foto_perfil" accept="image/*">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarUsuario()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Gestión de Gastronomía -->
<div class="modal fade" id="modalPlato" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPlatoTitle">Crear Plato</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formPlato" enctype="multipart/form-data">
                    <input type="hidden" id="plato_id" name="id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="plato_nombre" class="form-label">Nombre del Plato *</label>
                                <input type="text" class="form-control" id="plato_nombre" name="nombre" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="plato_categoria" class="form-label">Categoría *</label>
                                <select class="form-select" id="plato_categoria" name="categoria" required>
                                    <option value="">Seleccionar categoría</option>
                                    <option value="tradicional">Tradicional</option>
                                    <option value="fusion">Fusión</option>
                                    <option value="bebida">Bebida</option>
                                    <option value="postre">Postre</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="plato_descripcion" class="form-label">Descripción *</label>
                        <textarea class="form-control" id="plato_descripcion" name="descripcion" rows="4" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="plato_ingredientes" class="form-label">Ingredientes Principales</label>
                                <textarea class="form-control" id="plato_ingredientes" name="ingredientes" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="plato_imagen" class="form-label">Imagen del Plato</label>
                                <input type="file" class="form-control" id="plato_imagen" name="imagen" accept="image/*">
                                <div class="form-text">Formatos aceptados: JPG, PNG, GIF (máx. 5MB)</div>
                                <div id="imagen_actual" class="mt-2"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarPlato()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Gestión de Vocabulario Kichwa -->
<div class="modal fade" id="modalPalabra" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPalabraTitle">Crear Palabra</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formPalabra" enctype="multipart/form-data">
                    <input type="hidden" id="palabra_id" name="id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="palabra_kichwa" class="form-label">Palabra en Kichwa *</label>
                                <input type="text" class="form-control" id="palabra_kichwa" name="palabra_kichwa" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="palabra_traduccion" class="form-label">Traducción al Español *</label>
                                <input type="text" class="form-control" id="palabra_traduccion" name="traduccion_espanol" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="palabra_categoria" class="form-label">Categoría</label>
                                <select class="form-select" id="palabra_categoria" name="categoria">
                                    <option value="">Seleccionar categoría</option>
                                    <option value="saludos">Saludos</option>
                                    <option value="familia">Familia</option>
                                    <option value="naturaleza">Naturaleza</option>
                                    <option value="animales">Animales</option>
                                    <option value="comida">Comida</option>
                                    <option value="colores">Colores</option>
                                    <option value="numeros">Números</option>
                                    <option value="tiempo">Tiempo</option>
                                    <option value="cuerpo">Cuerpo Humano</option>
                                    <option value="casa">Casa</option>
                                    <option value="trabajo">Trabajo</option>
                                    <option value="emociones">Emociones</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="palabra_audio" class="form-label">Audio de Pronunciación</label>
                                <input type="file" class="form-control" id="palabra_audio" name="audio" accept="audio/*">
                                <div class="form-text">Formatos aceptados: MP3, WAV, OGG (máx. 10MB)</div>
                                <div id="audio_actual" class="mt-2"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarPalabra()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Gestión de Eventos -->
<div class="modal fade" id="modalEvento" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEventoTitle">Crear Evento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formEvento" enctype="multipart/form-data">
                    <input type="hidden" id="evento_id" name="id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="evento_titulo" class="form-label">Título del Evento *</label>
                                <input type="text" class="form-control" id="evento_titulo" name="titulo" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="evento_categoria" class="form-label">Categoría</label>
                                <select class="form-select" id="evento_categoria" name="categoria">
                                    <option value="">Seleccionar categoría</option>
                                    <option value="festival">Festival</option>
                                    <option value="ceremonia">Ceremonia</option>
                                    <option value="taller">Taller</option>
                                    <option value="exposicion">Exposición</option>
                                    <option value="mercado">Mercado</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="evento_descripcion" class="form-label">Descripción *</label>
                        <textarea class="form-control" id="evento_descripcion" name="descripcion" rows="4" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="evento_fecha" class="form-label">Fecha del Evento</label>
                                <input type="date" class="form-control" id="evento_fecha" name="fecha_evento">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="evento_hora" class="form-label">Hora del Evento</label>
                                <input type="time" class="form-control" id="evento_hora" name="hora_evento">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="evento_ubicacion" class="form-label">Ubicación</label>
                                <input type="text" class="form-control" id="evento_ubicacion" name="ubicacion">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="evento_imagen" class="form-label">Imagen del Evento</label>
                                <input type="file" class="form-control" id="evento_imagen" name="imagen" accept="image/*">
                                <div class="form-text">Formatos aceptados: JPG, PNG, GIF (máx. 5MB)</div>
                                <div id="imagen_evento_actual" class="mt-2"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarEvento()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Gestión de Rutas Turísticas -->
<div class="modal fade" id="modalRuta" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRutaTitle">Crear Ruta Turística</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formRuta" enctype="multipart/form-data">
                    <input type="hidden" id="ruta_id" name="id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="ruta_nombre" class="form-label">Nombre de la Ruta *</label>
                                <input type="text" class="form-control" id="ruta_nombre" name="nombre" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="ruta_dificultad" class="form-label">Dificultad</label>
                                <select class="form-select" id="ruta_dificultad" name="dificultad">
                                    <option value="">Seleccionar dificultad</option>
                                    <option value="facil">Fácil</option>
                                    <option value="moderada">Moderada</option>
                                    <option value="dificil">Difícil</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="ruta_descripcion" class="form-label">Descripción *</label>
                        <textarea class="form-control" id="ruta_descripcion" name="descripcion" rows="4" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="ruta_duracion" class="form-label">Duración (horas)</label>
                                <input type="number" class="form-control" id="ruta_duracion" name="duracion" min="1" max="24">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="ruta_distancia" class="form-label">Distancia (km)</label>
                                <input type="number" class="form-control" id="ruta_distancia" name="distancia" min="0.1" step="0.1">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="ruta_precio" class="form-label">Precio (USD)</label>
                                <input type="number" class="form-control" id="ruta_precio" name="precio" min="0" step="0.01">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="ruta_punto_inicio" class="form-label">Punto de Inicio</label>
                                <input type="text" class="form-control" id="ruta_punto_inicio" name="punto_inicio">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="ruta_punto_fin" class="form-label">Punto de Fin</label>
                                <input type="text" class="form-control" id="ruta_punto_fin" name="punto_fin">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="ruta_imagen" class="form-label">Imagen de la Ruta</label>
                        <input type="file" class="form-control" id="ruta_imagen" name="imagen" accept="image/*">
                        <div class="form-text">Formatos aceptados: JPG, PNG, GIF (máx. 5MB)</div>
                        <div id="imagen_ruta_actual" class="mt-2"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarRuta()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Vista Detallada de Evento -->
<div class="modal fade" id="modalDetalleEvento" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Vista Detallada del Evento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detalleEventoContent">
                <!-- Contenido cargado dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Vista Detallada de Ruta -->
<div class="modal fade" id="modalDetalleRuta" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Vista Detallada de la Ruta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detalleRutaContent">
                <!-- Contenido cargado dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
// Funciones para mostrar/ocultar campos específicos de artesano
document.getElementById('usuario_rol').addEventListener('change', function() {
    const camposArtesano = document.getElementById('campos_artesano');
    if (this.value === 'artesano') {
        camposArtesano.style.display = 'block';
    } else {
        camposArtesano.style.display = 'none';
    }
});

// Funciones para mostrar archivos actuales en edición
function mostrarImagenActual(elemento, rutaImagen) {
    if (rutaImagen) {
        elemento.innerHTML = `
            <div class="mt-2">
                <small class="text-muted">Imagen actual:</small><br>
                <img src="${rutaImagen}" alt="Imagen actual" style="max-width: 150px; max-height: 100px;" class="img-thumbnail">
            </div>
        `;
    }
}

function mostrarAudioActual(elemento, rutaAudio) {
    if (rutaAudio) {
        elemento.innerHTML = `
            <div class="mt-2">
                <small class="text-muted">Audio actual:</small><br>
                <audio controls style="width: 100%;">
                    <source src="${rutaAudio}" type="audio/mpeg">
                    Tu navegador no soporta el elemento audio.
                </audio>
            </div>
        `;
    }
}
</script>