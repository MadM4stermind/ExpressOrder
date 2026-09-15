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
            // Redondeo a 2 decimales por si acumulan libras/kg fraccionarios
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

    // Delegación de eventos: un solo listener para todos los botones "Agregar",
    // sin importar cuántos productos haya en la página.
    document.addEventListener('click', function (evento) {
        const boton = evento.target.closest('.btn-agregar-carrito');
        if (!boton) return;

        const inputCantidad = document.getElementById(boton.dataset.cantidadInput);
        const cantidad = parseFloat(inputCantidad.value);

        if (isNaN(cantidad) || cantidad <= 0) {
            inputCantidad.classList.add('is-invalid');
            return;
        }
        inputCantidad.classList.remove('is-invalid');

        agregarAlCarrito(
            {
                producto_id: parseInt(boton.dataset.id, 10),
                nombre: boton.dataset.nombre,
                precio: parseFloat(boton.dataset.precio),
                unidad_medida: boton.dataset.unidad,
            },
            cantidad
        );

        // Feedback visual rápido sin necesitar un toast completo todavía
        const textoOriginal = boton.textContent;
        boton.textContent = '✓ Agregado';
        boton.disabled = true;
        setTimeout(() => {
            boton.textContent = textoOriginal;
            boton.disabled = false;
        }, 900);
    });

    document.addEventListener('DOMContentLoaded', actualizarContadorCarrito);

    // Se expone por si otras vistas (carrito, checkout) necesitan leer/escribir directo — HU05 en adelante.
    window.ExpressOrderCarrito = { obtenerCarrito, guardarCarrito, agregarAlCarrito, actualizarContadorCarrito };
})();
