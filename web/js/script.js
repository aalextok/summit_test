window.BindBoard = {
  init:function() {
    $('select').dropdown();
    BindBoard.initForms();
  },
  initForms:function() {
    var me = BindBoard;
    var doc = document;
    var forms = me.forms;
    var proc = [];
    var formContainer = doc.querySelector('.card-edit-form');
    if (!formContainer) {
      return;
    }
    me.phone = doc.querySelector('.phone-model-inner');
    $(formContainer).on('change', me.updatePhone.bind(me));
    proc.forEach.call(formContainer.querySelectorAll('.card-edit-form > form'), function(formDOM) {
      var type = formDOM.querySelector('[data-typer]').value;
      forms[type].init(formDOM);
    });

    var typeField = formContainer.querySelector('[data-name="type"]');
    $(typeField).on('change', me.switchTo.bind(me));
    me.switchTo(typeField);
  },
  current:null,
  switchTo:function(select) {
    select = select.target || select;
    var me = this;
    var type = select.value;
    var forms = me.forms;
    if (me.current) {
      forms[me.current].hide();
    }
    me.current = type;
    forms[type].show();
    me.updatePhone();
  },
  updatePhone:function() {
    var me = this;
    me.forms[me.current].updatePhone(me.phone);
  },
  forms:{}
};

$(BindBoard.init);
