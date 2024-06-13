<tab name="{{ __('drivers::app.lead.form.create.heading') }}">
    <driver-information :data='@json(old('driver') ?: $lead->driver->driver)'></driver-information>
</tab>

@push('scripts')
    <script type="text/x-template" id="driver-information-template">
        <div class="driver-controls">
            <!-- Name -->
            <div class="form-group" :class="[errors.has('{!! $formScope ?? '' !!}driver[name]') ? 'has-error' : '']">
                <label for="driver[name]" class="required">{{ __('drivers::app.lead.form.create.name') }}</label>
    
                <input
                    type="text"
                    name="drivers[name]"
                    class="control"
                    id="driver[name]"
                    v-model="driver.name"
                    autocomplete="off"
                    placeholder="{{ __('admin::app.common.start-typing') }}"
                    v-validate="'required'"
                    data-vv-as="&quot;{{ __('drivers::app.lead.form.create.name') }}&quot;"
                    v-on:keyup="search"
                />

                <input
                    type="hidden"
                    name="drivers[driver_id]"
                    v-model="driver.driver_id"
                    v-validate="'required'"
                    data-vv-as="&quot;{{ __('drivers::app.lead.form.create.name') }}&quot;"
                    v-if="drivers.id"
                />

                <div class="lookup-results" v-if="search_drivers.length">
                    <ul>
                        <li v-for='(driver, index) in search_drivers' @click="addDriver(driver)">
                            <span>@{{ driver.name }}</span>
                        </li>

                        <li v-if="! search_drivers.length && ! is_searching">
                            <span>{{ __('admin::app.common.no-result-found') }}</span>
                        </li>

                        <li class="action" v-if="! is_searching" @click="addAsNew()">
                            <span>
                                + {{ __('admin::app.common.add-as') }}
                            </span> 
                        </li>
                    </ul>
                </div>

                <span class="control-error" v-if="errors.has('{!! $formScope ?? '' !!}driver[name]')">
                    @{{ errors.first('{!! $formScope ?? '' !!}driver[name]') }}
                </span>
            </div>

            <!-- Phone -->
            <div class="form-group contact-numbers">
                <label for="drivers[phone]">{{ __('drivers::app.lead.form.create.phone') }}</label>

                @include('admin::common.custom-attributes.edit.phone', ['formScope' => $formScope ?? ''])
                    
                <phone-component
                    :attribute="{'code': 'drivers[phone]', 'name': 'Contact Numbers'}"
                    :data="driver.phone"
                ></phone-component>
            </div>

            <!-- email -->
            <div class="form-group email">
                <label for="driver[email]">{{ __('drivers::app.lead.form.create.email') }}</label>

                @include('admin::common.custom-attributes.edit.email', ['formScope' => $formScope ?? ''])
                    
                <email-component
                    :attribute="{'code': 'drivers[email]', 'name': 'Email Address'}"
                    :data="driver.email"
                ></email-component>
            </div>
        </div>
    </script>

    <script>
        Vue.component('driver-information', {

            template: '#driver-information-template',
            
            props: ['data'],

            inject: ['$validator'],
            
            data: function () {
                return {
                    is_searching: false,

                    state: this.data ? 'old': '',

                    driver: this.data ? this.data : {
                        'name': ''
                    },

                    drivers: [],

                    search_drivers: [],
                }
            },

            mounted() {
                this.get();
            },

            methods: {
                get() {
                    this.$http.get("{{ route('drivers.lead.edit') }}", {
                        params: {
                                driver_id: `{{ $lead->driver?->driver_id }}`,
                                lead_id: `{{ $lead->id }}`
                            }
                        })
                        .then (function(response) {
                            this.driver = response.data.driver_lead;
                        })
                        .catch (function (error) {
                        })
                },

                search: debounce(function () {
                    this.state = '';

                    this.driver = {
                        'name': this.drivers.name
                    };

                    this.is_searching = true;

                    var self = this;

                    this.$http.get("{{ route('drivers.information.search') }}", {
                        params: {
                            query: this.drivers.name
                        }})
                        .then (function(response) {

                            self.driver = [];

                            self.search_drivers = response.data;

                            self.is_searching = false;
                        })
                        .catch (function (error) {
                            self.is_searching = false;
                        })
                }, 500),

                addDriver: function(result) {
                    this.state = 'old';
                    
                    this.driver = result;
                },

                addAsNew: function() {
                    this.state = 'new';
                }
            }
        });
    </script>
@endpush