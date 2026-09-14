<!-- Age Verification Modal (Exact Star Importers Specification) -->
<div id="ageVerificationModal"
    style="display: none; position: fixed; inset: 0; z-index: 999999; background: rgba(0, 0, 0, 0.75); backdrop-filter: blur(2px); align-items: center; justify-content: center; padding: 20px; box-sizing: border-box;">
    
    <div id="ageVerificationCard"
        style="background: #ffffff; border-radius: 6px; max-width: 580px; width: 100%; padding: 40px 35px 35px 35px; box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4); text-align: center; position: relative; animation: ageModalPop 0.25s ease-out;">
        
        <!-- Wholesale Logo -->
        <div style="margin-bottom: 12px; display: flex; justify-content: center; align-items: center;">
            <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name', 'Carolina Prime Distributors') }} Logo"
                style="max-width: 260px; max-height: 95px; width: auto; height: auto; object-fit: contain; display: block;"
                onerror="this.style.display='none'; document.getElementById('ageFallbackLogo').style.display='block';" />
            
            <!-- Fallback text branding if image not loaded -->
            <div id="ageFallbackLogo" style="display: none; font-size: 24px; font-weight: 800; color: #3e4093; letter-spacing: 1px; text-transform: uppercase;">
                {{ config('app.name', 'Wholesale Distributors') }}
            </div>
        </div>

        <!-- Heading -->
        <h2 style="font-size: 28px; font-weight: 700; color: #111827; margin: 12px 0 14px 0; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; letter-spacing: -0.3px;">
            Age Verification
        </h2>

        <!-- Divider Line -->
        <hr style="border: 0; border-top: 1px solid #e5e7eb; margin: 0 0 20px 0;" />

        <!-- Warning Disclaimer Text -->
        <p style="font-size: 15px; line-height: 1.65; color: #1f2937; margin: 0 0 28px 0; font-weight: 500; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; padding: 0 5px;">
            You must be 21 years of age or older to view this website.By entering this website, you agree that you are 21 years of age or older. Falsifying your age for the purpose of purchasing products from this web site is illegal and punishable by law.
        </p>

        <!-- Buttons Container -->
        <div style="display: flex; gap: 18px; justify-content: center; align-items: center; flex-wrap: wrap;">
            <button id="btnAgeEnter" type="button"
                style="background-color: #3e4093; color: #ffffff; border: none; border-radius: 4px; padding: 12px 42px; font-size: 15px; font-weight: 700; letter-spacing: 1.2px; text-transform: uppercase; cursor: pointer; transition: all 0.15s ease; min-width: 150px; font-family: 'Inter', sans-serif; box-shadow: 0 2px 4px rgba(62, 64, 147, 0.25);"
                onmouseover="this.style.backgroundColor='#323479'; this.style.transform='translateY(-1px)';"
                onmouseout="this.style.backgroundColor='#3e4093'; this.style.transform='translateY(0)';">
                ENTER
            </button>

            <button id="btnAgeUnderage" type="button"
                style="background-color: #3e4093; color: #ffffff; border: none; border-radius: 4px; padding: 12px 42px; font-size: 15px; font-weight: 700; letter-spacing: 1.2px; text-transform: uppercase; cursor: pointer; transition: all 0.15s ease; min-width: 150px; font-family: 'Inter', sans-serif; box-shadow: 0 2px 4px rgba(62, 64, 147, 0.25);"
                onmouseover="this.style.backgroundColor='#323479'; this.style.transform='translateY(-1px)';"
                onmouseout="this.style.backgroundColor='#3e4093'; this.style.transform='translateY(0)';">
                UNDERAGE
            </button>
        </div>
    </div>
</div>

<style>
@keyframes ageModalPop {
    from {
        opacity: 0;
        transform: scale(0.94);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}
</style>

<script>
(function() {
    function initAgeVerification() {
        var modal = document.getElementById('ageVerificationModal');
        var btnEnter = document.getElementById('btnAgeEnter');
        var btnUnderage = document.getElementById('btnAgeUnderage');

        if (!modal || !btnEnter || !btnUnderage) return;

        // Check if user is already age verified
        var isVerified = (sessionStorage.getItem('DontShowPopups') === 'true') || 
                         (localStorage.getItem('age_verified') === 'true');

        if (!isVerified) {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        } else {
            modal.style.display = 'none';
        }

        // Action: ENTER
        btnEnter.addEventListener('click', function(e) {
            e.preventDefault();
            sessionStorage.setItem('DontShowPopups', 'true');
            localStorage.setItem('age_verified', 'true');

            modal.style.transition = 'opacity 0.25s ease';
            modal.style.opacity = '0';
            setTimeout(function() {
                modal.style.display = 'none';
                document.body.style.overflow = '';
            }, 250);
        });

        // Action: UNDERAGE
        btnUnderage.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = 'https://www.google.com/';
        });

        // Prevent dismissing by pressing ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.style.display !== 'none') {
                e.preventDefault();
                e.stopPropagation();
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAgeVerification);
    } else {
        initAgeVerification();
    }
})();
</script>
