@extends('dashboard.layouts.master')
@section('content')
    <div class="container-xl">
            <div class="row row-cards">
              <div class="col-md-6">
                <div class="card">
                  <div class="card-body">
                    <div class="accordion" id="accordion-default">
                      <div class="accordion-item">
                        <button class="accordion-header" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-1-default" aria-expanded="true">
                          <div class="accordion-header-text">
                            <h4>What makes Tabler different from other UI frameworks?</h4>
                          </div>
                          <div class="accordion-header-toggle">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-down -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                              <path d="M6 9l6 6l6 -6"></path>
                            </svg>
                          </div>
                        </button>
                        <div id="collapse-1-default" class="accordion-collapse collapse show" data-bs-parent="#accordion-default">
                          <div class="accordion-body">
                            Tabler offers a modern, responsive design with a clean aesthetic, built on Bootstrap for ease of use and flexibility.
                          </div>
                        </div>
                      </div>
                      <div class="accordion-item">
                        <button class="accordion-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-2-default" aria-expanded="false">
                          <div class="accordion-header-text">
                            <h4>How can I customize Tabler components to fit my design needs?</h4>
                          </div>
                          <div class="accordion-header-toggle">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-down -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                              <path d="M6 9l6 6l6 -6"></path>
                            </svg>
                          </div>
                        </button>
                        <div id="collapse-2-default" class="accordion-collapse collapse" data-bs-parent="#accordion-default">
                          <div class="accordion-body">
                            You can customize Tabler components using CSS variables, SCSS, and utility classes to match your design preferences.
                          </div>
                        </div>
                      </div>
                      <div class="accordion-item">
                        <button class="accordion-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-3-default" aria-expanded="false">
                          <div class="accordion-header-text">
                            <h4>Is Tabler optimized for performance and fast loading times?</h4>
                          </div>
                          <div class="accordion-header-toggle">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-down -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                              <path d="M6 9l6 6l6 -6"></path>
                            </svg>
                          </div>
                        </button>
                        <div id="collapse-3-default" class="accordion-collapse collapse" data-bs-parent="#accordion-default">
                          <div class="accordion-body">
                            Yes, Tabler is lightweight, optimized for modern browsers, and follows best practices for fast loading and efficiency.
                          </div>
                        </div>
                      </div>
                      <div class="accordion-item">
                        <button class="accordion-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-4-default" aria-expanded="false">
                          <div class="accordion-header-text">
                            <h4>How accessible are Tabler components?</h4>
                          </div>
                          <div class="accordion-header-toggle">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-down -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                              <path d="M6 9l6 6l6 -6"></path>
                            </svg>
                          </div>
                        </button>
                        <div id="collapse-4-default" class="accordion-collapse collapse" data-bs-parent="#accordion-default">
                          <div class="accordion-body">
                            Tabler components follow WAI-ARIA standards and support keyboard navigation, screen readers, and accessibility best practices.
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card">
                  <div class="accordion accordion-flush" id="accordion-flush">
                    <div class="accordion-item">
                      <button class="accordion-header" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-1-flush" aria-expanded="true">
                        <div class="accordion-header-text">
                          <h4>What makes Tabler different from other UI frameworks?</h4>
                        </div>
                        <div class="accordion-header-toggle">
                          <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-down -->
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                            <path d="M6 9l6 6l6 -6"></path>
                          </svg>
                        </div>
                      </button>
                      <div id="collapse-1-flush" class="accordion-collapse collapse show" data-bs-parent="#accordion-flush">
                        <div class="accordion-body">
                          Tabler offers a modern, responsive design with a clean aesthetic, built on Bootstrap for ease of use and flexibility.
                        </div>
                      </div>
                    </div>
                    <div class="accordion-item">
                      <button class="accordion-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-2-flush" aria-expanded="false">
                        <div class="accordion-header-text">
                          <h4>How can I customize Tabler components to fit my design needs?</h4>
                        </div>
                        <div class="accordion-header-toggle">
                          <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-down -->
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                            <path d="M6 9l6 6l6 -6"></path>
                          </svg>
                        </div>
                      </button>
                      <div id="collapse-2-flush" class="accordion-collapse collapse" data-bs-parent="#accordion-flush">
                        <div class="accordion-body">
                          You can customize Tabler components using CSS variables, SCSS, and utility classes to match your design preferences.
                        </div>
                      </div>
                    </div>
                    <div class="accordion-item">
                      <button class="accordion-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-3-flush" aria-expanded="false">
                        <div class="accordion-header-text">
                          <h4>Is Tabler optimized for performance and fast loading times?</h4>
                        </div>
                        <div class="accordion-header-toggle">
                          <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-down -->
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                            <path d="M6 9l6 6l6 -6"></path>
                          </svg>
                        </div>
                      </button>
                      <div id="collapse-3-flush" class="accordion-collapse collapse" data-bs-parent="#accordion-flush">
                        <div class="accordion-body">
                          Yes, Tabler is lightweight, optimized for modern browsers, and follows best practices for fast loading and efficiency.
                        </div>
                      </div>
                    </div>
                    <div class="accordion-item">
                      <button class="accordion-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-4-flush" aria-expanded="false">
                        <div class="accordion-header-text">
                          <h4>How accessible are Tabler components?</h4>
                        </div>
                        <div class="accordion-header-toggle">
                          <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-down -->
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                            <path d="M6 9l6 6l6 -6"></path>
                          </svg>
                        </div>
                      </button>
                      <div id="collapse-4-flush" class="accordion-collapse collapse" data-bs-parent="#accordion-flush">
                        <div class="accordion-body">
                          Tabler components follow WAI-ARIA standards and support keyboard navigation, screen readers, and accessibility best practices.
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card">
                  <div class="card-body">
                    <div class="accordion accordion-tabs" id="accordion-tabs">
                      <div class="accordion-item">
                        <button class="accordion-header" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-1-tabs" aria-expanded="true">
                          <div class="accordion-header-text">
                            <h4>What makes Tabler different from other UI frameworks?</h4>
                          </div>
                          <div class="accordion-header-toggle">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-down -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                              <path d="M6 9l6 6l6 -6"></path>
                            </svg>
                          </div>
                        </button>
                        <div id="collapse-1-tabs" class="accordion-collapse collapse show" data-bs-parent="#accordion-tabs">
                          <div class="accordion-body">
                            Tabler offers a modern, responsive design with a clean aesthetic, built on Bootstrap for ease of use and flexibility.
                          </div>
                        </div>
                      </div>
                      <div class="accordion-item">
                        <button class="accordion-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-2-tabs" aria-expanded="false">
                          <div class="accordion-header-text">
                            <h4>How can I customize Tabler components to fit my design needs?</h4>
                          </div>
                          <div class="accordion-header-toggle">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-down -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                              <path d="M6 9l6 6l6 -6"></path>
                            </svg>
                          </div>
                        </button>
                        <div id="collapse-2-tabs" class="accordion-collapse collapse" data-bs-parent="#accordion-tabs">
                          <div class="accordion-body">
                            You can customize Tabler components using CSS variables, SCSS, and utility classes to match your design preferences.
                          </div>
                        </div>
                      </div>
                      <div class="accordion-item">
                        <button class="accordion-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-3-tabs" aria-expanded="false">
                          <div class="accordion-header-text">
                            <h4>Is Tabler optimized for performance and fast loading times?</h4>
                          </div>
                          <div class="accordion-header-toggle">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-down -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                              <path d="M6 9l6 6l6 -6"></path>
                            </svg>
                          </div>
                        </button>
                        <div id="collapse-3-tabs" class="accordion-collapse collapse" data-bs-parent="#accordion-tabs">
                          <div class="accordion-body">
                            Yes, Tabler is lightweight, optimized for modern browsers, and follows best practices for fast loading and efficiency.
                          </div>
                        </div>
                      </div>
                      <div class="accordion-item">
                        <button class="accordion-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-4-tabs" aria-expanded="false">
                          <div class="accordion-header-text">
                            <h4>How accessible are Tabler components?</h4>
                          </div>
                          <div class="accordion-header-toggle">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-down -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                              <path d="M6 9l6 6l6 -6"></path>
                            </svg>
                          </div>
                        </button>
                        <div id="collapse-4-tabs" class="accordion-collapse collapse" data-bs-parent="#accordion-tabs">
                          <div class="accordion-body">
                            Tabler components follow WAI-ARIA standards and support keyboard navigation, screen readers, and accessibility best practices.
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card">
                  <div class="card-body">
                    <div class="accordion accordion-inverted" id="accordion-inverted">
                      <div class="accordion-item">
                        <button class="accordion-header" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-1-inverted" aria-expanded="true">
                          <div class="accordion-header-text">
                            <h4>What makes Tabler different from other UI frameworks?</h4>
                          </div>
                          <div class="accordion-header-toggle">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-down -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                              <path d="M6 9l6 6l6 -6"></path>
                            </svg>
                          </div>
                        </button>
                        <div id="collapse-1-inverted" class="accordion-collapse collapse show" data-bs-parent="#accordion-inverted">
                          <div class="accordion-body">
                            Tabler offers a modern, responsive design with a clean aesthetic, built on Bootstrap for ease of use and flexibility.
                          </div>
                        </div>
                      </div>
                      <div class="accordion-item">
                        <button class="accordion-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-2-inverted" aria-expanded="false">
                          <div class="accordion-header-text">
                            <h4>How can I customize Tabler components to fit my design needs?</h4>
                          </div>
                          <div class="accordion-header-toggle">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-down -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                              <path d="M6 9l6 6l6 -6"></path>
                            </svg>
                          </div>
                        </button>
                        <div id="collapse-2-inverted" class="accordion-collapse collapse" data-bs-parent="#accordion-inverted">
                          <div class="accordion-body">
                            You can customize Tabler components using CSS variables, SCSS, and utility classes to match your design preferences.
                          </div>
                        </div>
                      </div>
                      <div class="accordion-item">
                        <button class="accordion-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-3-inverted" aria-expanded="false">
                          <div class="accordion-header-text">
                            <h4>Is Tabler optimized for performance and fast loading times?</h4>
                          </div>
                          <div class="accordion-header-toggle">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-down -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                              <path d="M6 9l6 6l6 -6"></path>
                            </svg>
                          </div>
                        </button>
                        <div id="collapse-3-inverted" class="accordion-collapse collapse" data-bs-parent="#accordion-inverted">
                          <div class="accordion-body">
                            Yes, Tabler is lightweight, optimized for modern browsers, and follows best practices for fast loading and efficiency.
                          </div>
                        </div>
                      </div>
                      <div class="accordion-item">
                        <button class="accordion-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-4-inverted" aria-expanded="false">
                          <div class="accordion-header-text">
                            <h4>How accessible are Tabler components?</h4>
                          </div>
                          <div class="accordion-header-toggle">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-down -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                              <path d="M6 9l6 6l6 -6"></path>
                            </svg>
                          </div>
                        </button>
                        <div id="collapse-4-inverted" class="accordion-collapse collapse" data-bs-parent="#accordion-inverted">
                          <div class="accordion-body">
                            Tabler components follow WAI-ARIA standards and support keyboard navigation, screen readers, and accessibility best practices.
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card">
                  <div class="card-body">
                    <div class="accordion accordion-inverted accordion-plus" id="accordion-inverted-plus">
                      <div class="accordion-item">
                        <button class="accordion-header" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-1-inverted-plus" aria-expanded="true">
                          <div class="accordion-header-text">
                            <h4>What makes Tabler different from other UI frameworks?</h4>
                          </div>
                          <div class="accordion-header-toggle accordion-header-toggle-plus">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                              <path d="M12 5l0 14"></path>
                              <path d="M5 12l14 0"></path>
                            </svg>
                          </div>
                        </button>
                        <div id="collapse-1-inverted-plus" class="accordion-collapse collapse show" data-bs-parent="#accordion-inverted-plus">
                          <div class="accordion-body">
                            Tabler offers a modern, responsive design with a clean aesthetic, built on Bootstrap for ease of use and flexibility.
                          </div>
                        </div>
                      </div>
                      <div class="accordion-item">
                        <button class="accordion-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-2-inverted-plus" aria-expanded="false">
                          <div class="accordion-header-text">
                            <h4>How can I customize Tabler components to fit my design needs?</h4>
                          </div>
                          <div class="accordion-header-toggle accordion-header-toggle-plus">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                              <path d="M12 5l0 14"></path>
                              <path d="M5 12l14 0"></path>
                            </svg>
                          </div>
                        </button>
                        <div id="collapse-2-inverted-plus" class="accordion-collapse collapse" data-bs-parent="#accordion-inverted-plus">
                          <div class="accordion-body">
                            You can customize Tabler components using CSS variables, SCSS, and utility classes to match your design preferences.
                          </div>
                        </div>
                      </div>
                      <div class="accordion-item">
                        <button class="accordion-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-3-inverted-plus" aria-expanded="false">
                          <div class="accordion-header-text">
                            <h4>Is Tabler optimized for performance and fast loading times?</h4>
                          </div>
                          <div class="accordion-header-toggle accordion-header-toggle-plus">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                              <path d="M12 5l0 14"></path>
                              <path d="M5 12l14 0"></path>
                            </svg>
                          </div>
                        </button>
                        <div id="collapse-3-inverted-plus" class="accordion-collapse collapse" data-bs-parent="#accordion-inverted-plus">
                          <div class="accordion-body">
                            Yes, Tabler is lightweight, optimized for modern browsers, and follows best practices for fast loading and efficiency.
                          </div>
                        </div>
                      </div>
                      <div class="accordion-item">
                        <button class="accordion-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-4-inverted-plus" aria-expanded="false">
                          <div class="accordion-header-text">
                            <h4>How accessible are Tabler components?</h4>
                          </div>
                          <div class="accordion-header-toggle accordion-header-toggle-plus">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                              <path d="M12 5l0 14"></path>
                              <path d="M5 12l14 0"></path>
                            </svg>
                          </div>
                        </button>
                        <div id="collapse-4-inverted-plus" class="accordion-collapse collapse" data-bs-parent="#accordion-inverted-plus">
                          <div class="accordion-body">
                            Tabler components follow WAI-ARIA standards and support keyboard navigation, screen readers, and accessibility best practices.
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card">
                  <div class="card-body">
                    <div class="accordion" id="accordion-icons">
                      <div class="accordion-item">
                        <button class="accordion-header" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-1-icons" aria-expanded="true">
                          <div class="accordion-header-icon">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/link -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                              <path d="M9 15l6 -6"></path>
                              <path d="M11 6l.463 -.536a5 5 0 0 1 7.071 7.072l-.534 .464"></path>
                              <path d="M13 18l-.397 .534a5.068 5.068 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463"></path>
                            </svg>
                          </div>
                          <div class="accordion-header-text">
                            <h4>What makes Tabler different from other UI frameworks?</h4>
                          </div>
                          <div class="accordion-header-toggle">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-down -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                              <path d="M6 9l6 6l6 -6"></path>
                            </svg>
                          </div>
                        </button>
                        <div id="collapse-1-icons" class="accordion-collapse collapse show" data-bs-parent="#accordion-icons">
                          <div class="accordion-body">
                            Tabler offers a modern, responsive design with a clean aesthetic, built on Bootstrap for ease of use and flexibility.
                          </div>
                        </div>
                      </div>
                      <div class="accordion-item">
                        <button class="accordion-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-2-icons" aria-expanded="false">
                          <div class="accordion-header-icon">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/link -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                              <path d="M9 15l6 -6"></path>
                              <path d="M11 6l.463 -.536a5 5 0 0 1 7.071 7.072l-.534 .464"></path>
                              <path d="M13 18l-.397 .534a5.068 5.068 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463"></path>
                            </svg>
                          </div>
                          <div class="accordion-header-text">
                            <h4>How can I customize Tabler components to fit my design needs?</h4>
                          </div>
                          <div class="accordion-header-toggle">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-down -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                              <path d="M6 9l6 6l6 -6"></path>
                            </svg>
                          </div>
                        </button>
                        <div id="collapse-2-icons" class="accordion-collapse collapse" data-bs-parent="#accordion-icons">
                          <div class="accordion-body">
                            You can customize Tabler components using CSS variables, SCSS, and utility classes to match your design preferences.
                          </div>
                        </div>
                      </div>
                      <div class="accordion-item">
                        <button class="accordion-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-3-icons" aria-expanded="false">
                          <div class="accordion-header-icon">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/link -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                              <path d="M9 15l6 -6"></path>
                              <path d="M11 6l.463 -.536a5 5 0 0 1 7.071 7.072l-.534 .464"></path>
                              <path d="M13 18l-.397 .534a5.068 5.068 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463"></path>
                            </svg>
                          </div>
                          <div class="accordion-header-text">
                            <h4>Is Tabler optimized for performance and fast loading times?</h4>
                          </div>
                          <div class="accordion-header-toggle">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-down -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                              <path d="M6 9l6 6l6 -6"></path>
                            </svg>
                          </div>
                        </button>
                        <div id="collapse-3-icons" class="accordion-collapse collapse" data-bs-parent="#accordion-icons">
                          <div class="accordion-body">
                            Yes, Tabler is lightweight, optimized for modern browsers, and follows best practices for fast loading and efficiency.
                          </div>
                        </div>
                      </div>
                      <div class="accordion-item">
                        <button class="accordion-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-4-icons" aria-expanded="false">
                          <div class="accordion-header-icon">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/link -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                              <path d="M9 15l6 -6"></path>
                              <path d="M11 6l.463 -.536a5 5 0 0 1 7.071 7.072l-.534 .464"></path>
                              <path d="M13 18l-.397 .534a5.068 5.068 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463"></path>
                            </svg>
                          </div>
                          <div class="accordion-header-text">
                            <h4>How accessible are Tabler components?</h4>
                          </div>
                          <div class="accordion-header-toggle">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-down -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                              <path d="M6 9l6 6l6 -6"></path>
                            </svg>
                          </div>
                        </button>
                        <div id="collapse-4-icons" class="accordion-collapse collapse" data-bs-parent="#accordion-icons">
                          <div class="accordion-body">
                            Tabler components follow WAI-ARIA standards and support keyboard navigation, screen readers, and accessibility best practices.
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
    </div>
@endsection