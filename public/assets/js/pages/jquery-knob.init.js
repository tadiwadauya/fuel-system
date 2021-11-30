$(function(){$('[data-plugin="knob"]').knob({
        'format' : function (value) {
            return value + '%' + ' left';
        },
    draw : function(){
        $('[data-plugin="knob"]').css("font-size","25px");
        //this.g.fillText('label', x, y);
    }
    }
)
});
