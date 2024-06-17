<style>
    .login_slider_container {
        width: 100%;
        height: 100%;
        position: relative;
    }
    
    .login_slide {
        position: absolute;
        inset: 0;
        opacity: 0;
        transition: 200ms opacity ease-in-out;
        transition-delay: 200ms;
    }
    
    .login_slide a:first-child,
    .login_slide > a:first-child > img {
        display: block;
        width: 100%;
        height: 100%;
    }
    
    .login_slide > a:first-child > img {
        object-fit: cover;
        object-position: center;
    }
    
    .login_slide[data-active] {
        opacity: 1;
        transition-delay: 0ms;
        z-index: 1;
    }
    
    span[data-slider-position] {
        position: absolute;
        bottom: 0.5rem;
        right: 1rem;
        color: white;
        font-size: 1.2rem;
        font-weight: bold;
        z-index: 2;
    }
    
    .login_slider_button {
        position: absolute;
        background: none;
        border: none;
        font-size: 50%;
        top: 50%;
        z-index: 2;
        transform: translateY(-50%);
        background: rgba(128, 0, 128, 0.596);
        color: white;
        cursor: pointer;
        border-radius: 50%;
        font-size: 1.5rem;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 50px;
        height: 50px;
    }
    
    .login_slider_button.lgs_prev {
        left: 1rem;
    }
    
    .login_slider_button.lgs_next {
        right: 1rem;
    } 
    
    .login_slider_button:hover {
        border: 2px solid white;
    }
    
    .lgs_text_container,
    .lgs_button {
        position: absolute;
        z-index: 2;
        transform: translateX(-50%);
        color: white;
    }
    
    .lgs_text_container {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        align-items: center;
        justify-content: center;
        top: 50%;
        left: 50%;
        transform: translateY(-50%) translateX(-50%);
    }
    
    .lgs_title {
        font-size: 2rem;
        font-weight: bold;
        text-align: center;
    }
    
    .lgs_subtitle {
        font-size: 1.3rem;
        text-align: center;
    }
    
    .lgs_button {
        height: 45px;
        min-width: 165px;
        bottom: 1.2rem;
        left: 50%;
        padding: 0 1rem;
        background-color: purple;
        font-weight: bold;
        border: none;
        border-radius: 30px;
    }
    
    @media screen and (max-width: 1024px) {
        .login_slider_container,
        body > div > div.row > div.col.min-vh-100.overflow-hidden > div {
            width: 100vw;
        }
    }
    
    @media screen and (max-width: 1250px) {
        #pp_noa_container {
            flex-direction: column;
            gap: 16px !important;
        }
        
        #pp_noa_container span {
            font-size: 4rem !important;
        }
    }
    
    @media screen and (max-width: 768px) {
        #pp_noa_container img {
            width: 80px !important;
        }
        #pp_noa_container span {
            font-size: 3.2rem !important;
        }
    }
    
    @media screen and (max-width: 425px) {
        #pp_noa_container span {
            font-size: 3rem !important;
        }
    }
</style>

<script type="text/javascript">
    lgs_buttons = document.querySelectorAll('[data-slider-button]');
    lgs_position = document.querySelector('[data-slider-position]');
    
    slide_length = document.querySelectorAll('.login_slide').length;
    allowChangeSlides = !([0, 1].includes(slide_length));
    
    // msPerSlide = 8000;
    
    // slideSwitcherID = setInterval(switchSlides, msPerSlide);
    
    if(slide_length > 0) {
        lgs_position.innerHTML = '1/' + slide_length;
    } 
    
    
    lgs_buttons.forEach(button => {
        button.addEventListener('click', () => {
            if(!allowChangeSlides) {
                return;
            }
            
            switchSlides(button);
        });
    });
    
    function switchSlides(button) {
        if(button === undefined) {
            button = lgs_buttons[1]; 
        }
        
        offset = button.dataset.sliderButton === 'next' ? 1 : -1;
        slides = button.closest('[data-lgs-slider]').querySelector('[data-lgs-slides]');
        
        activeSlide = slides.querySelector("[data-active]");
        newIndex = [...slides.children].indexOf(activeSlide) + offset;
        
        if(newIndex < 0) {
            newIndex = slides.children.length - 1;
        } else if(newIndex >= slides.children.length) {
            newIndex = 0;
        } 
        
        // clearInterval(slideSwitcherID);
        // slideSwitcherID = setInterval(switchSlides, msPerSlide);
        
        
        lgs_position.innerHTML = `${newIndex + 1}/${document.querySelectorAll('.login_slide').length}`;
        slides.children[newIndex].dataset.active = true;
        delete activeSlide.dataset.active;
    }
</script>