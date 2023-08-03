{{-- Seperator --}}
<span class="block w-full absolute left-0 my-[5px] border border-[#E9E9E9]"></span>

<v-customer-group-price></v-customer-group-price>

@inject('customerGroupRepository', 'Webkul\Customer\Repositories\CustomerGroupRepository')

@pushOnce('scripts')
    <script type="text/x-template" id="v-customer-group-price-template">
        <div>
            <!-- Header -->
            <div class="flex items-center justify-between mt-[6px] py-[15px]">
                <p class="text-gray-800 text-[16px] py-[10px] font-semibold">
                    @lang('admin::app.catalog.products.edit.price.group.title')
                </p>

                <p
                    class="text-blue-600 cursor-pointer"
                    @click="resetForm(); $refs.groupPriceCreateModal.open()"
                >
                    @lang('admin::app.catalog.products.edit.price.group.create-btn')
                </p>
            </div>

            <!-- Content -->
            <div class="grid">
                <!-- Card -->
                <div
                    class="flex flex-col gap-[8px] py-[10px]"
                    v-for="(item, index) in prices"
                >
                    <!-- Hidden Inputs -->
                    <input
                        type="hidden"
                        :name="'customer_group_prices[' + item.id + '][customer_group_id]'"
                        :value="item.customer_group_id"
                    />

                    <input
                        type="hidden"
                        :name="'customer_group_prices[' + item.id + '][qty]'"
                        :value="item.qty"
                    />

                    <input
                        type="hidden"
                        :name="'customer_group_prices[' + item.id + '][value_type]'"
                        :value="item.value_type"
                    />

                    <input
                        type="hidden"
                        :name="'customer_group_prices[' + item.id + '][value]'"
                        :value="item.amount"
                    />

                    <div class="flex justify-between">
                        <p class="text-gray-600 font-semibold">
                            @{{ getGroupNameById(item.customer_group_id) }}
                        </p>

                        <p
                            class="text-blue-600 cursor-pointer"
                            @click="selectedPrice = item; $refs.groupPriceCreateModal.open()"
                        >
                            @lang('admin::app.catalog.products.edit.price.group.edit-btn')
                        </p>
                    </div>

                    <p
                        class="text-gray-600"
                        v-if="item.value_type == 'fixed'"
                    >
                        @{{ "@lang('admin::app.catalog.products.edit.price.group.fixed-group-price-info')".replace(':qty', item.qty).replace(':price', item.amount) }}
                    </p>

                    <p
                        class="text-gray-600"
                        v-else
                    >
                        @{{ "@lang('admin::app.catalog.products.edit.price.group.fixed-group-price-info')".replace(':qty', item.qty).replace(':price', item.amount) }}
                    </p>
                </div>

                <!-- Empty Container -->
                <div
                    class="flex gap-[20px] items-center py-[10px]"
                    v-if="! prices.length"
                >
                    <img
                        src="{{ bagisto_asset('images/icon-discount.svg') }}"
                        class="w-[80px] h-[80px] border border-dashed border-gray-300 rounded-[4px]"
                    />

                    <div class="flex flex-col gap-[6px]">
                        <p class="text-[16px] text-gray-400 font-semibold">
                            @lang('admin::app.catalog.products.edit.price.group.add-group-price')
                        </p>

                        <p class="text-gray-400">
                            @lang('admin::app.catalog.products.edit.price.group.empty-info')
                        </p>
                    </div>
                </div>
            </div>

            <!-- Form Modal -->
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                <form @submit="handleSubmit($event, create)">
                    <!-- Customer Create Modal -->
                    <x-admin::modal ref="groupPriceCreateModal">
                        <x-slot:header>
                            <!-- Modal Header -->
                            <p
                                class="text-[18px] text-gray-800 font-bold"
                                v-if="! selectedPrice.id"
                            >
                                @lang('admin::app.catalog.products.edit.price.group.create.create-title')
                            </p>

                            <p
                                class="text-[18px] text-gray-800 font-bold"
                                v-else
                            >
                                @lang('admin::app.catalog.products.edit.price.group.create.update-title')
                            </p>    
                        </x-slot:header>
        
                        <x-slot:content>
                            <!-- Modal Content -->
                            <div class="px-[16px] py-[10px] border-b-[1px] border-gray-300">
                                {!! view_render_event('bagisto.admin.catalog.products.create_form.general.controls.before') !!}

                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.catalog.products.edit.price.group.create.customer-group')*
                                    </x-admin::form.control-group.label>
        
                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="customer_group_id"
                                        v-model="selectedPrice.customer_group_id"
                                        rules="required"
                                        :label="trans('admin::app.catalog.products.edit.price.group.create.customer-group')"
                                    >
                                        <option
                                            v-for="group in groups"
                                            :value="group.id"
                                        >
                                            @{{ group.name }}
                                        </option>
                                    </x-admin::form.control-group.control>
        
                                    <x-admin::form.control-group.error control-name="customer_group_id"></x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <div class="flex gap-[16px]">
                                    <x-admin::form.control-group class="flex-1">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.catalog.products.edit.price.group.create.qty')*
                                        </x-admin::form.control-group.label>
            
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="qty"
                                            v-model="selectedPrice.qty"
                                            rules="required|numeric|min_value:1"
                                            :label="trans('admin::app.catalog.products.edit.price.group.create.qty')"
                                        >
                                        </x-admin::form.control-group.control>
            
                                        <x-admin::form.control-group.error control-name="qty"></x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    <x-admin::form.control-group class="flex-1">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.catalog.products.edit.price.group.create.price-type')*
                                        </x-admin::form.control-group.label>
            
                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="value_type"
                                            v-model="selectedPrice.value_type"
                                            rules="required"
                                            :label="trans('admin::app.catalog.products.edit.price.group.create.price-type')"
                                        >
                                            <option value="fixed">
                                                @lang('admin::app.catalog.products.edit.price.group.create.fixed')
                                            </option>

                                            <option value="discount">
                                                @lang('admin::app.catalog.products.edit.price.group.create.discount')
                                            </option>
                                        </x-admin::form.control-group.control>
            
                                        <x-admin::form.control-group.error control-name="value_type"></x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    <x-admin::form.control-group class="flex-1">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.catalog.products.edit.price.group.create.price')*
                                        </x-admin::form.control-group.label>
            
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="amount"
                                            v-model="selectedPrice.amount"
                                            ::rules="{required: true, decimal: true, min_value: 0, ...(selectedPrice.value_type === 'discount' ? {max_value: 100} : {})}"
                                            :label="trans('admin::app.catalog.products.edit.price.group.create.price')"
                                        >
                                        </x-admin::form.control-group.control>
            
                                        <x-admin::form.control-group.error control-name="amount"></x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
                                </div>

                                {!! view_render_event('bagisto.admin.catalog.products.create_form.general.controls.before') !!}
                            </div>
                        </x-slot:content>
        
                        <x-slot:footer>
                            <!-- Modal Submission -->
                            <div class="flex gap-x-[10px] items-center">
                                <button
                                    type="button"
                                    class="text-red-600 font-semibold whitespace-nowrap px-[12px] py-[6px] border-[2px] border-transparent rounded-[6px] transition-all hover:bg-gray-100 cursor-pointer"
                                    @click="remove"
                                    v-if="selectedPrice.id"
                                >
                                    @lang('admin::app.catalog.products.edit.price.group.create.delete-btn')
                                </button>

                                <button 
                                    type="submit"
                                    class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                                >
                                    @lang('admin::app.catalog.products.edit.price.group.create.save-btn')
                                </button>
                            </div>
                        </x-slot:footer>
                    </x-admin::modal>
                </form>
            </x-admin::form>
        </div>
    </script>

    <script type="module">
        app.component('v-customer-group-price', {
            template: '#v-customer-group-price-template',

            data: function() {
                return {
                    groups: @json($customerGroupRepository->all()),

                    prices: @json($product->customer_group_prices),

                    selectedPrice: {
                        customer_group_id: null,
                        qty: 0,
                        value_type: 'fixed',
                        amount: 0,
                    }
                }
            },

            methods: {
                getGroupNameById(id) {
                    let group = this.groups.find(group => group.id == id);

                    return group ? group.name : '';
                },

                create(params) {
                    if (this.selectedPrice.id == undefined) {
                        params.id = 'price_' + this.prices.length;

                        this.prices.push(params);
                    } else {
                        const indexToUpdate = this.prices.findIndex(price => price.id === this.selectedPrice.id);

                        this.prices[indexToUpdate] = this.selectedPrice;
                    }

                    this.resetForm();

                    this.$refs.groupPriceCreateModal.close();
                },

                resetForm() {
                    this.selectedPrice = {
                        customer_group_id: null,
                        qty: 0,
                        value_type: 'fixed',
                        amount: 0,
                    };
                },

                remove() {
                    let index = this.prices.indexOf(this.selectedPrice);

                    this.prices.splice(index, 1);

                    this.resetForm();

                    this.$refs.groupPriceCreateModal.close();
                }
            }
        });
    </script>
@endPushOnce