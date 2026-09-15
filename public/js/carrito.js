(function () {
    'use strict';

    const CARRITO_KEY = 'expressorder_carrito';

    function obtenerCarrito() {
        const data = localStorage.getItem(CARRITO_KEY);
        return data ? JSON.parse(data) : [];
    }

    function guardarCarrito(carrito) {
        localStorage.setItem(CARRITO_KEY, JSON.stringify(carrito));
        actualizarContadorCarrito();
    }

    function actualizarContadorCarrito() {
        const carrito = obtenerCarrito();
        const totalItems = carrito.reduce((suma, item) => suma + item.cantidad, 0);
        const badge = document.getElementById('carrito-contador');

        if (!badge) return;

        if (totalItems > 0) {
            badge.textContent = Math.round(totalItems * 100) / 100;
            badge.classList.remove('d-none');
        } else {
            badge.classList.add('d-none');
        }
    }

    // HU04: agrega un producto, o incrementa la cantidad si ya estaba en el carrito.
    function agregarAlCarrito(producto, cantidad) {
        if (!cantidad || cantidad <= 0) return;

        const carrito = obtenerCarrito();
        const existente = carrito.find((item) => item.producto_id === producto.producto_id);

        if (existente) {
            existente.cantidad += cantidad;
        } else {
            carrito.push({ ...producto, cantidad });
        }

        guardarCarrito(carrito);
    }

    // HU05: cambia la cantidad de un producto ya existente en el carrito.
    function actualizarCantidad(productoId, nuevaCantidad) {
        if (!nuevaCantidad || nuevaCantidad <= 0) return;

        const carrito = obtenerCarrito();
        const item = carrito.find((i) => i.producto_id === productoId);
        if (!item) return;

        item.cantidad = nuevaCantidad;
        guardarCarrito(carrito);
    }

    // HU05: quita un producto por completo del carrito.
    function eliminarDelCarrito(productoId) {
        const carrito = obtenerCarrito().filter((i) => i.producto_id !== productoId);
        guardarCarrito(carrito);
    }

    // HU05: dibuja la tabla del carrito completa a partir de lo que hay en localStorage.
    // Se llama al cargar /carrito, y de nuevo cada vez que cambia cantidad o se quita algo.
    function renderizarVistaCarrito() {
        const contenedorFilas = document.getElementById('carrito-filas');
        if (!contenedorFilas) return; // no estamos en la página del carrito

        const carrito = obtenerCarrito();
        const tabla = document.getElementById('tabla-carrito');
        const vacio = document.getElementById('carrito-vacio');

        if (carrito.length === 0) {
            tabla.classList.add('d-none');
            vacio.classList.remove('d-none');
            return;
        }

        tabla.classList.remove('d-none');
        vacio.classList.add('d-none');
        contenedorFilas.innerHTML = '';

        let total = 0;

        carrito.forEach((item) => {
            const subtotal = item.precio * item.cantidad;
            total += subtotal;

            const esPorPeso = item.unidad_medida === 'lb' || item.unidad_medida === 'kg';
            const step = esPorPeso ? '0.25' : '1';
            const min = esPorPeso ? '0.25' : '1';

            const fila = document.createElement('tr');
            fila.innerHTML =
                '<td>' + item.nombre + '</td>' +
                '<td>RD$ ' + item.precio.toFixed(2) + ' / ' + item.unidad_medida + '</td>' +
                '<td>' +
                    '<input type="number" class="form-control form-control-sm input-cantidad-carrito" ' +
                    'data-id="' + item.producto_id + '" value="' + item.cantidad + '" min="' + min + '" step="' + step + '">' +
                '</td>' +
                '<td>RD$ ' + subtotal.toFixed(2) + '</td>' +
                '<td>' +
                    '<button type="button" class="btn btn-sm btn-outline-danger btn-quitar-carrito" ' +
                    'data-id="' + item.producto_id + '">Quitar</button>' +
                '</td>';

            contenedorFilas.appendChild(fila);
        });

        document.getElementById('carrito-total').textContent = 'RD$ ' + total.toFixed(2);
    }

    // --- Eventos ---

    // Un solo listener de click para "Agregar" (catálogo) y "Quitar" (carrito).
    document.addEventListener('click', function (evento) {
        const botonAgregar = evento.target.closest('.btn-agregar-carrito');
        if (botonAgregar) {
            const inputCantidad = document.getElementById(botonAgregar.dataset.cantidadInput);
            const cantidad = parseFloat(inputCantidad.value);

            if (isNaN(cantidad) || cantidad <= 0) {
                inputCantidad.classList.add('is-invalid');
                return;
            }
            inputCantidad.classList.remove('is-invalid');

            agregarAlCarrito(
                {
                    producto_id: parseInt(botonAgregar.dataset.id, 10),
                    nombre: botonAgregar.dataset.nombre,
                    precio: parseFloat(botonAgregar.dataset.precio),
                    unidad_medida: botonAgregar.dataset.unidad,
                },
                cantidad
            );

            const textoOriginal = botonAgregar.textContent;
            botonAgregar.textContent = '✓ Agregado';
            botonAgregar.disabled = true;
            setTimeout(() => {
                botonAgregar.textContent = textoOriginal;
                botonAgregar.disabled = false;
            }, 900);

            return;
        }

        const botonQuitar = evento.target.closest('.btn-quitar-carrito');
        if (botonQuitar) {
            eliminarDelCarrito(parseInt(botonQuitar.dataset.id, 10));
            renderizarVistaCarrito();
        }
    });

    // "change" (no "input") para no interrumpir al usuario mientras escribe la cantidad.
    document.addEventListener('change', function (evento) {
        const inputCantidad = evento.target.closest('.input-cantidad-carrito');
        if (!inputCantidad) return;

        const productoId = parseInt(inputCantidad.dataset.id, 10);
        const cantidad = parseFloat(inputCantidad.value);

        if (isNaN(cantidad) || cantidad <= 0) {
            inputCantidad.classList.add('is-invalid');
            return;
        }

        actualizarCantidad(productoId, cantidad);
        renderizarVistaCarrito(); // recalcula subtotal y total en pantalla
    });

    document.addEventListener('DOMContentLoaded', actualizarContadorCarrito);

    window.ExpressOrderCarrito = {
        obtenerCarrito,
        guardarCarrito,
        agregarAlCarrito,
        actualizarCantidad,
        eliminarDelCarrito,
        renderizarVistaCarrito,
        actualizarContadorCarrito,
    };
})();
