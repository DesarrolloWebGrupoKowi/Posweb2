<div class="mt-2 d-flex flex-wrap gap-2 justify-content-center align-items-center">
    @if (count($items) > 0)
        <p class="m-0 text-secondary d-md-none" style="font-weight: 500; font-size: smaller">Mostrando la página
            {!! $items->currentPage() !!} de {!! $items->lastPage() !!}
        </p>
    @endif
    {!! $items->links('pagination::bootstrap-5') !!} <!-- Asegúrate de usar la vista adecuada -->
</div>


<script>
    document.addEventListener('DOMContentLoaded', e => {
        $paginateText = document.querySelector('.pagination');
        $nodes = $paginateText.parentNode.parentNode.childNodes[3].childNodes;
        $nodes[1].parentNode.removeChild($nodes[1]);

        $paginate = document.querySelectorAll('.pagination li');
        // if ($paginate.length > 0) {
        //     if ($paginate[0].querySelector('span'))
        //         $paginate[0].querySelector('span').textContent = 'Anterior';
        //     if ($paginate[0].querySelector('a'))
        //         $paginate[0].querySelector('a').textContent = 'Anterior';
        //     if ($paginate[$paginate.length - 1].querySelector('span')) $paginate[$paginate.length - 1]
        //         .querySelector('span').textContent = 'Siguiente';
        //     if ($paginate[$paginate.length - 1].querySelector('a')) $paginate[$paginate.length - 1]
        //         .querySelector('a').textContent = 'Siguiente';
        // }
    })
</script>
