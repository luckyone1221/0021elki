$(document).ready(function (){
  //pp
  //$("body > *:last-child").after('<div class="pixel-perfect" style="background-image: url(screen/checkout.png);"></div>')


  //phone link js
  function setCallLink(){
    let links = document.querySelectorAll('.number-link-js');

    for (let link of links){
      let href = link.getAttribute('href');

      href = href.replace(/\s+|\(|\)|\-/g, '');
      link.setAttribute('href', 'tel:'+href);
    }
  }
  setCallLink();

  //header phone number split
  function phoneNuberSplit() {
    let container = document.querySelector('.tel-txt-js');
    if (!container) return

    //get number and get read of spaces
    let number = container.innerHTML;
    number = number.replace(/\s/g, '')
    if (number.length < 8) return

    //get small and big
    let small = number.substr(0, 7) + ' ';
    let big = number.substr(7, );

    //clean cont
    container.innerHTML = '';
    let smallSpan = document.createElement('span');
    smallSpan.innerHTML = small;
    container.appendChild(smallSpan);

    let bigSpan = document.createElement('span');
    bigSpan.innerHTML = big;
    container.appendChild(bigSpan);
  }
  phoneNuberSplit();


  //submenu toggle
  $('.dd-link').click(function (){
    event.preventDefault();
    //hide prev
    let self = this;
    $('.dd-link').each(function (){
      if (self !== this) {
        $(this).removeClass('active');
        $(this.parentElement.parentElement).find('.submenu-2').slideUp(function (){
          $(this).removeClass('active');
        });
      }
    });

    //opne this
    $(this).toggleClass('active');
    $(this.parentElement.parentElement).find('.submenu-2').slideToggle(function (){
      $(this).toggleClass('active');
    });
  });

  //mob category toggle
  $('.category-txt').click(function (){
    $(this.parentElement).find('.submenu').slideToggle(function (){
      $(this).toggleClass('active');
    });
  });
  //mob menu js
  function closeMenu(){
    $('body').removeClass('fixed');
    $('.header__menu-js').removeClass('active');
  }

  $('.burger-btn-js').click(function (){
    //$(this).toggleClass('active');
    $('body').toggleClass('fixed');
    $('.header__menu-js').toggleClass('active');
  });

  //
  $('body').click(function (){
    if (event.target === this && $('body').hasClass('fixed')){
      closeMenu();
    }
  });
  window.addEventListener('resize', function (){
    if (window.matchMedia("(min-width: 1200px)").matches) {
      closeMenu();
    }
  }, {passive: true});


  $('.pastel-html-js').each(function (){
    let self = this;
    window.setTimeout(function (){
      let html = self.innerHTML.replace(/&lt;/g, '<').replace(/&gt;/g, '>');
      self.innerHTML = '';
      self.insertAdjacentHTML('afterbegin', html);
    }, 1);
  });

  //bredcrumbs slider
  let breadSl = new Swiper('.breadcrumb-slider-js', {
    slidesPerView: 'auto',
    // spaceBetween: 30,
    freeMode: true,
    freeModeMomentum: true,
    // spaceBetween: 30,
    watchOverflow: true,
  });
  //
  let prodCard = new Swiper('.prod-slider-js', {
    slidesPerView: 1,
    spaceBetween: 30,
  });
  //showSelf(['#product',])


  function showSelf(arr){
    for (let el of arr){
      let self = document.querySelector(el);
      console.log(self);
    }
  }
  //category-mob-tog-js
  $('.category-mob-tog-js').click(function (){
    $(this).toggleClass('active');
    $('.category-list-js').slideToggle(function (){
      $(this).toggleClass('active');
    });
  });

  $('.callback-link-js').click(function (){
    event.preventDefault();
    $('#button_feedback').click();
  });
  //checkout
  window.setTimeout(function (){
    $('#button-payment-method').closest('.qc-step').addClass('continue-step');
    $('#shipping-method').closest('.qc-step').addClass('shipping-method-parent');
    $('#shipping-method').closest('.qc-step')
    $('.shipping-method-parent').insertAfter('#payment-method');
    $('.shipping-method-parent').insertAfter('#payment-method');
    $('.qc-step.continue-step').insertAfter('.shipping-method-parent');
  }, 0);

  let LabelsReadyId = window.setInterval(function (){
    let checkBoxes = document.querySelectorAll('.qc-step td input[type=radio]');
    if (checkBoxes.length < 4) return

    for (let item of checkBoxes){
      let circle = document.createElement('div');
      circle.classList.add('chb-circle')
      item.style.display = 'none';
      item.parentElement.appendChild(circle);

    }

    // for (let label of labels) {
    //   console.log(label);
    //
    //   if (label) {
    //     setLabelWork.call(label);
    //   }
    //
    //   function setLabelWork(){
    //     let radio = this.closest('tr').querySelector('input[type=radio]');
    //     if (!radio) return
    //   }
    //
    // }

    window.clearInterval(LabelsReadyId);
  }, 100);

  $('.price-txt-js').each(function (){
    let str = this.innerHTML.replace(/\s+/g,'');
    let currency = str.replace(/\d+/g,'');
    let price = str.replace(/\D+/g,'');

    let revPrice = price.split('').reverse().join('');
    revPrice = revPrice.match(/.{1,3}/g);

    for(let [index, str] of Object.entries(revPrice)){
      console.log(index, str);
      revPrice[index] = str.split('').reverse().join('');
    }

    price = revPrice.reverse().join(' ');
    this.innerHTML = price+' '+currency;
  });
  //calc-price-js
  $('.calc-price-js').each(function (){
    // 1 replace trim delete spaces, 2 replace leaves only numbers
    let oldPrice = Number(this.getAttribute('data-price').replace(/\s+/g,'').replace(/\D+/g,''));
    let newPrice = Number(this.getAttribute('data-special').replace(/\s+/g,'').replace(/\D+/g,''));
    this.innerHTML = Math.floor((oldPrice - newPrice) / oldPrice * 100);
  });


});