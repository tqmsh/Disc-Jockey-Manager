<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
<div class="flex items-center justify-between px-4 mx-0 bg-white shadow rounded-lg mt--20">
  <div class=" text-4xl font-light">
    Pick a package below to top up your credits
  </div>
  <div class="gap-x-4 p-6">
    <div class="text-black">Total Credits</div>
    <div class="flex justify-center text-3xl font-semibold">{{ Auth::user()->vendor->credits }}</div>
  </div>
</div>





<div class="py-4 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">    
      <div class="space-y-8 grid justify-stretch grid-cols-3 sm:gap-6 xl:gap-10 lg:space-y-0">
          <!-- Pricing Card -->
          
              <!-- DO NOT REMOVE FORM TAG-->
                </form>

          
<!-- #1 -->

          <!-- Pricing Card -->
          <div class="relative flex-col bg-red space-y-8 max-w-lg text-center text-black bg-white  border-1 border-gray-300 dark:border-white-600 xl:p-8 dark:bg-white-800 dark:text-white hover:shadow-lg hover:-translate-y-2 transition-transform duration-300">
              <div class="flex justify-center items-baseline my-8 mt-3">
                  <span class="mr-2 text-5xl font-light text-black">$29</span>
              </div>
              <h3 class="mb-4 text-2xl font-bold text-black px-30">10 credits</h3>

              <!-- List -->
                <form action="{{ route('paypal')}}" method="post">
                    @csrf
                    <input type="hidden" name="price" value="29">
                    <input type="hidden" name="credits" value="10">
                    <button type="submit" class=" rounded-full text-black bg-yellow-500  hover:bg-yellow-500/90 focus:ring-4 focus:ring-[#F7BE38]/50 font-medium rounded-xg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-[#F7BE38]/50 mr-2 mb-2">
<svg class="mr-2 -ml-1 w-4 h-4" aria-hidden="true" focusable="false" data-prefix="fab" data-icon="paypal" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M111.4 295.9c-3.5 19.2-17.4 108.7-21.5 134-.3 1.8-1 2.5-3 2.5H12.3c-7.6 0-13.1-6.6-12.1-13.9L58.8 46.6c1.5-9.6 10.1-16.9 20-16.9 152.3 0 165.1-3.7 204 11.4 60.1 23.3 65.6 79.5 44 140.3-21.5 62.6-72.5 89.5-140.1 90.3-43.4 .7-69.5-7-75.3 24.2zM357.1 152c-1.8-1.3-2.5-1.8-3 1.3-2 11.4-5.1 22.5-8.8 33.6-39.9 113.8-150.5 103.9-204.5 103.9-6.1 0-10.1 3.3-10.9 9.4-22.6 140.4-27.1 169.7-27.1 169.7-1 7.1 3.5 12.9 10.6 12.9h63.5c8.6 0 15.7-6.3 17.4-14.9 .7-5.4-1.1 6.1 14.4-91.3 4.6-22 14.3-19.7 29.3-19.7 71 0 126.4-28.8 142.9-112.3 6.5-34.8 4.6-71.4-23.8-92.6z"></path></svg>
Buy Now
</button>
                </form>
                <p class="font-light text-black sm:text-base dark:text-black">      $2.9 per credit      </p>
<!-- #2 -->

          </div>
          <!-- Pricing Card -->
          <div class="relative flex-col bg-red space-y-8 max-w-lg text-center text-black bg-white  border-1 border-gray-300  dark:border-white-600 xl:p-8 dark:bg-white-800 dark:text-white hover:shadow-lg hover:-translate-y-2 transition-transform duration-300">
              <div class="flex justify-center items-baseline my-8 mt-3">
                  <span class="mr-2 text-5xl font-light text-black">$99</span>
              </div>
              <h3 class="mb-4 text-2xl font-bold text-black px-30">25 credits</h3>

              <!-- List -->
                <form action="{{ route('paypal')}}" method="post">
                    @csrf
                    <input type="hidden" name="price" value="99">
                    <input type="hidden" name="credits" value="25">
                    <button type="submit" class=" rounded-full text-black bg-yellow-500 hover:bg-yellow-500/90 focus:ring-4 focus:ring-[#F7BE38]/50 font-medium rounded-xg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-[#F7BE38]/50 mr-2 mb-2">
<svg class="mr-2 -ml-1 w-4 h-4" aria-hidden="true" focusable="false" data-prefix="fab" data-icon="paypal" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M111.4 295.9c-3.5 19.2-17.4 108.7-21.5 134-.3 1.8-1 2.5-3 2.5H12.3c-7.6 0-13.1-6.6-12.1-13.9L58.8 46.6c1.5-9.6 10.1-16.9 20-16.9 152.3 0 165.1-3.7 204 11.4 60.1 23.3 65.6 79.5 44 140.3-21.5 62.6-72.5 89.5-140.1 90.3-43.4 .7-69.5-7-75.3 24.2zM357.1 152c-1.8-1.3-2.5-1.8-3 1.3-2 11.4-5.1 22.5-8.8 33.6-39.9 113.8-150.5 103.9-204.5 103.9-6.1 0-10.1 3.3-10.9 9.4-22.6 140.4-27.1 169.7-27.1 169.7-1 7.1 3.5 12.9 10.6 12.9h63.5c8.6 0 15.7-6.3 17.4-14.9 .7-5.4-1.1 6.1 14.4-91.3 4.6-22 14.3-19.7 29.3-19.7 71 0 126.4-28.8 142.9-112.3 6.5-34.8 4.6-71.4-23.8-92.6z"></path></svg>
Buy Now
</button>
                </form>
                <p class="font-light text-black sm:text-base dark:text-black">      $4 per credit      </p>

          </div>
<!-- #3 -->

            <div class="relative flex-col bg-red space-y-8 max-w-lg text-center text-black bg-white  border-1 border-gray-300 dark:border-white-600 xl:p-8 dark:bg-white-800 dark:text-white hover:shadow-lg hover:-translate-y-2 transition-transform duration-300">
              <div class="flex justify-center items-baseline my-8 mt-3">
                  <span class="mr-2 text-5xl font-light text-black">$499</span>
              </div>
              <h3 class="mb-4 text-2xl font-bold text-black px-30">100 credits</h3>

              <!-- List -->
                <form action="{{ route('paypal')}}" method="post">
                    @csrf
                    <input type="hidden" name="price" value="499">
                    <input type="hidden" name="credits" value="100">
                    <button type="submit" class=" rounded-full text-black bg-yellow-500 hover:bg-yellow-500/90 focus:ring-4 focus:ring-[#F7BE38]/50 font-medium rounded-xg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-[#F7BE38]/50 mr-2 mb-2">
<svg class="mr-2 -ml-1 w-4 h-4" aria-hidden="true" focusable="false" data-prefix="fab" data-icon="paypal" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M111.4 295.9c-3.5 19.2-17.4 108.7-21.5 134-.3 1.8-1 2.5-3 2.5H12.3c-7.6 0-13.1-6.6-12.1-13.9L58.8 46.6c1.5-9.6 10.1-16.9 20-16.9 152.3 0 165.1-3.7 204 11.4 60.1 23.3 65.6 79.5 44 140.3-21.5 62.6-72.5 89.5-140.1 90.3-43.4 .7-69.5-7-75.3 24.2zM357.1 152c-1.8-1.3-2.5-1.8-3 1.3-2 11.4-5.1 22.5-8.8 33.6-39.9 113.8-150.5 103.9-204.5 103.9-6.1 0-10.1 3.3-10.9 9.4-22.6 140.4-27.1 169.7-27.1 169.7-1 7.1 3.5 12.9 10.6 12.9h63.5c8.6 0 15.7-6.3 17.4-14.9 .7-5.4-1.1 6.1 14.4-91.3 4.6-22 14.3-19.7 29.3-19.7 71 0 126.4-28.8 142.9-112.3 6.5-34.8 4.6-71.4-23.8-92.6z"></path></svg>
Buy Now
</button>
                </form>
                <p class="font-light text-black sm:text-base dark:text-black">      $5 per credit      </p>

          </div>
<!-- #4 -->
          
          <div class="relative flex-col bg-red space-y-8 max-w-lg text-center text-black bg-white  border-1 border-gray-300 dark:border-white-600 xl:p-8 dark:bg-white-800 dark:text-white hover:shadow-lg hover:-translate-y-2 transition-transform duration-300">
              <div class="flex justify-center items-baseline my-8 mt-3">
                  <span class="mr-2 text-5xl font-light text-black">$850</span>
              </div>
              <h3 class="mb-4 text-2xl font-bold text-black px-30">250 credits</h3>

              <!-- List -->
                <form action="{{ route('paypal')}}" method="post">
                    @csrf
                    <input type="hidden" name="price" value="850">
                    <input type="hidden" name="credits" value="250">
                    <button type="submit" class=" rounded-full text-black bg-yellow-500 hover:bg-yellow-500/90 focus:ring-4 focus:ring-[#F7BE38]/50 font-medium rounded-xg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-[#F7BE38]/50 mr-2 mb-2">
<svg class="mr-2 -ml-1 w-4 h-4" aria-hidden="true" focusable="false" data-prefix="fab" data-icon="paypal" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M111.4 295.9c-3.5 19.2-17.4 108.7-21.5 134-.3 1.8-1 2.5-3 2.5H12.3c-7.6 0-13.1-6.6-12.1-13.9L58.8 46.6c1.5-9.6 10.1-16.9 20-16.9 152.3 0 165.1-3.7 204 11.4 60.1 23.3 65.6 79.5 44 140.3-21.5 62.6-72.5 89.5-140.1 90.3-43.4 .7-69.5-7-75.3 24.2zM357.1 152c-1.8-1.3-2.5-1.8-3 1.3-2 11.4-5.1 22.5-8.8 33.6-39.9 113.8-150.5 103.9-204.5 103.9-6.1 0-10.1 3.3-10.9 9.4-22.6 140.4-27.1 169.7-27.1 169.7-1 7.1 3.5 12.9 10.6 12.9h63.5c8.6 0 15.7-6.3 17.4-14.9 .7-5.4-1.1 6.1 14.4-91.3 4.6-22 14.3-19.7 29.3-19.7 71 0 126.4-28.8 142.9-112.3 6.5-34.8 4.6-71.4-23.8-92.6z"></path></svg>
Buy Now
</button>
                </form>
                <p class="font-light text-black sm:text-base dark:text-black">      $3.4 per credit      </p>

          </div>
<!-- #5 -->

          <div class="relative flex-col bg-red space-y-8 max-w-lg text-center text-black bg-white  border-1 border-gray-300 dark:border-white-600 xl:p-8 dark:bg-white-800 dark:text-white hover:shadow-lg hover:-translate-y-2 transition-transform duration-300">
              <div class="flex justify-center items-baseline my-8 mt-3">
                  <span class="mr-2 text-5xl font-light text-black">$1200</span>
              </div>
              <h3 class="mb-4 text-2xl font-bold text-black px-30">500 credits</h3>

              <!-- List -->
                <form action="{{ route('paypal')}}" method="post">
                    @csrf
                    <input type="hidden" name="price" value="1200">
                    <input type="hidden" name="credits" value="500">
                    <button type="submit" class=" rounded-full text-black bg-yellow-500 hover:bg-yellow-500/90 focus:ring-4 focus:ring-[#F7BE38]/50 font-medium rounded-xg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-[#F7BE38]/50 mr-2 mb-2">
<svg class="mr-2 -ml-1 w-4 h-4" aria-hidden="true" focusable="false" data-prefix="fab" data-icon="paypal" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M111.4 295.9c-3.5 19.2-17.4 108.7-21.5 134-.3 1.8-1 2.5-3 2.5H12.3c-7.6 0-13.1-6.6-12.1-13.9L58.8 46.6c1.5-9.6 10.1-16.9 20-16.9 152.3 0 165.1-3.7 204 11.4 60.1 23.3 65.6 79.5 44 140.3-21.5 62.6-72.5 89.5-140.1 90.3-43.4 .7-69.5-7-75.3 24.2zM357.1 152c-1.8-1.3-2.5-1.8-3 1.3-2 11.4-5.1 22.5-8.8 33.6-39.9 113.8-150.5 103.9-204.5 103.9-6.1 0-10.1 3.3-10.9 9.4-22.6 140.4-27.1 169.7-27.1 169.7-1 7.1 3.5 12.9 10.6 12.9h63.5c8.6 0 15.7-6.3 17.4-14.9 .7-5.4-1.1 6.1 14.4-91.3 4.6-22 14.3-19.7 29.3-19.7 71 0 126.4-28.8 142.9-112.3 6.5-34.8 4.6-71.4-23.8-92.6z"></path></svg>
Buy Now
</button>
                </form>
                <p class="font-light text-black sm:text-base dark:text-black">      $2.4 per credit      </p>

          </div>
<!-- #6 -->
          <div class="relative flex-col bg-red space-y-8 max-w-lg text-center text-black bg-white  border-1 border-gray-300 dark:border-white-600 xl:p-8 dark:bg-white-800 dark:text-white hover:shadow-lg hover:-translate-y-2 transition-transform duration-300">
              <div class="flex justify-center items-baseline my-8 mt-3">
                  <span class="mr-2 text-5xl font-light text-black">$1500</span>
              </div>
              <h3 class="mb-4 text-2xl font-bold text-black px-30">1000 credits</h3>

              <!-- List -->
                <form action="{{ route('paypal')}}" method="post">
                    @csrf
                    <input type="hidden" name="price" value="1500">
                    <input type="hidden" name="credits" value="1000">
                    <button type="submit" class=" rounded-full text-black bg-yellow-500 hover:bg-yellow-500/90 focus:ring-4 focus:ring-[#F7BE38]/50 font-medium rounded-xg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-[#F7BE38]/50 mr-2 mb-2">
<svg class="mr-2 -ml-1 w-4 h-4" aria-hidden="true" focusable="false" data-prefix="fab" data-icon="paypal" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M111.4 295.9c-3.5 19.2-17.4 108.7-21.5 134-.3 1.8-1 2.5-3 2.5H12.3c-7.6 0-13.1-6.6-12.1-13.9L58.8 46.6c1.5-9.6 10.1-16.9 20-16.9 152.3 0 165.1-3.7 204 11.4 60.1 23.3 65.6 79.5 44 140.3-21.5 62.6-72.5 89.5-140.1 90.3-43.4 .7-69.5-7-75.3 24.2zM357.1 152c-1.8-1.3-2.5-1.8-3 1.3-2 11.4-5.1 22.5-8.8 33.6-39.9 113.8-150.5 103.9-204.5 103.9-6.1 0-10.1 3.3-10.9 9.4-22.6 140.4-27.1 169.7-27.1 169.7-1 7.1 3.5 12.9 10.6 12.9h63.5c8.6 0 15.7-6.3 17.4-14.9 .7-5.4-1.1 6.1 14.4-91.3 4.6-22 14.3-19.7 29.3-19.7 71 0 126.4-28.8 142.9-112.3 6.5-34.8 4.6-71.4-23.8-92.6z"></path></svg>
Buy Now
</button>
                </form>
                <p class="font-light text-black sm:text-base dark:text-black">      $1.5 per credit      </p>

          </div>
      </div>
  </div>
</body>
</html>