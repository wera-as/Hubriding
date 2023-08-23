$(document).ready(function() {
    const updateBars = () => {
        const visitors = $('.visits');
        const int = visitors.map(function() {
            return parseInt($(this).text());
        }).get();
        const max = Math.max(...int);

        visitors.each(function() {
            const visitor = $(this);
            const bar = visitor.find('.bar');
            const previousWidth = bar.data('previousWidth') || 0;
            const width = Math.round((parseInt(visitor.text()) / max) * 100);
            bar.css({
                width: previousWidth + '%',
                //opacity: '1'
            });
            setTimeout(() => {
                bar.css({
                    width: width + '%',
                    //opacity: '1'
                });
                bar.data('previousWidth', width);
            }, 0);
        });
    };

    const initTable = () => {
        const tableElement = $('#DataTables_Table_0');
        if (tableElement.length === 0) {
            return;
        }

        const table = tableElement.DataTable();
        tableElement.on('draw.dt', function() {
            setTimeout(updateBars, 0);
        });

        setTimeout(updateBars, 0);
    };

    setTimeout(initTable, 0);
});
