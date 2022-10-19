<?php
    //use App\Models\....;
    use App\Models\OrderPackage;
    use App\Models\OrderProduct;
    

    /*
    |-------------------------------------------------------------------------------------------------------
    | Start from here
    |-------------------------------------------------------------------------------------------------------
    */

        //defaultEbaskatPrimeId_hd() == Aliexpress //1
        //defaultEbaskatPrimeBbId_hd() == Bigbuy //2
        function dropshipingVendorLabel_hh($merchant_id)
        {
            if( $merchant_id == defaultEbaskatPrimeId_hd())
            {
                return "Aliexpress";
            }
            else if($merchant_id == defaultEbaskatPrimeBbId_hd())
            {
                return "Bigbuy";
            }
            else{
                return "Ebaskat";
            }
        }

    /*
    |-------------------------------------------------------------------------------------------------------
    | Ebaskat all sub-category for bigbuy sub-category match with ebaskat sub-category
    |-------------------------------------------------------------------------------------------------------
    */

        function subCategory_hh()
        {
            return [
                "rompers"=>["id"=>1,"parentId"=>1],"skirt"=>["id"=>2,"parentId"=>1],"plus-size-clothes"=>["id"=>3,"parentId"=>1],"dresses"=>["id"=>4,"parentId"=>1],"jackets-coats"=>["id"=>5,"parentId"=>1],"bodysuits"=>["id"=>6,"parentId"=>1],"jeans"=>["id"=>7,"parentId"=>1],"swimsuit"=>["id"=>8,"parentId"=>1],"jumpsuits"=>["id"=>9,"parentId"=>1],"tops-tees"=>["id"=>10,"parentId"=>1],"pants-capris"=>["id"=>11,"parentId"=>1],"muslim-fashion"=>["id"=>12,"parentId"=>1],"women-tops"=>["id"=>13,"parentId"=>1],"sweaters"=>["id"=>14,"parentId"=>1],"hoodies-sweatshirts"=>["id"=>15,"parentId"=>1],"dress"=>["id"=>16,"parentId"=>1],"bottoms"=>["id"=>17,"parentId"=>1],"blouses-shirts"=>["id"=>18,"parentId"=>1],"suits-sets"=>["id"=>19,"parentId"=>1],"mens-sets"=>["id"=>20,"parentId"=>2],"sweaters-mens-clothing"=>["id"=>21,"parentId"=>2],"jeans-mens-clothing"=>["id"=>22,"parentId"=>2],"pants"=>["id"=>23,"parentId"=>2],"suits-blazers"=>["id"=>24,"parentId"=>2],"casual-shorts"=>["id"=>25,"parentId"=>2],"tops-tees-mens-clothing"=>["id"=>26,"parentId"=>2],"shirts"=>["id"=>27,"parentId"=>2],"hoodies-sweatshirts-mens-clothing"=>["id"=>28,"parentId"=>2],"board-shorts"=>["id"=>29,"parentId"=>2],"jackets-coats-mens-clothing"=>["id"=>30,"parentId"=>2],"baby-stroller-accessories"=>["id"=>31,"parentId"=>16],"pregnancy-maternity"=>["id"=>32,"parentId"=>16],"kidsbaby-accessories"=>["id"=>33,"parentId"=>16],"girls-baby-clothing"=>["id"=>34,"parentId"=>16],"safety-equipment"=>["id"=>35,"parentId"=>16],"baby-bedding"=>["id"=>36,"parentId"=>16],"toilet-training"=>["id"=>37,"parentId"=>16],"baby-souvenirs"=>["id"=>38,"parentId"=>16],"childrens-shoes"=>["id"=>39,"parentId"=>16],"boys-baby-clothing"=>["id"=>40,"parentId"=>16],"baby-care"=>["id"=>41,"parentId"=>16],"car-seats-accessories"=>["id"=>42,"parentId"=>16],"baby-furniture"=>["id"=>43,"parentId"=>16],"nappy-changing"=>["id"=>44,"parentId"=>16],"feeding"=>["id"=>45,"parentId"=>16],"boys-clothing"=>["id"=>46,"parentId"=>16],"baby-food"=>["id"=>47,"parentId"=>16],"matching-family-outfits"=>["id"=>48,"parentId"=>16],"baby-shoes"=>["id"=>49,"parentId"=>16],"activity-gear"=>["id"=>50,"parentId"=>16],"girls-clothing"=>["id"=>51,"parentId"=>16],"womens-bracelet-watches"=>["id"=>52,"parentId"=>9],"mens-watches"=>["id"=>53,"parentId"=>9],"lovers-watches"=>["id"=>54,"parentId"=>9],"pocket-fob-watches"=>["id"=>55,"parentId"=>9],"childrens-watches"=>["id"=>56,"parentId"=>9],"watch-accessories"=>["id"=>57,"parentId"=>9],"womens-watches"=>["id"=>58,"parentId"=>9],"mobile-phone-accessories"=>["id"=>59,"parentId"=>3],"communication-equipments"=>["id"=>60,"parentId"=>3],"refurbished-phones"=>["id"=>61,"parentId"=>3],"mobile-phone-parts"=>["id"=>62,"parentId"=>3],"cellphones"=>["id"=>63,"parentId"=>3],"iPhones"=>["id"=>64,"parentId"=>3],"phone-bags-cases"=>["id"=>65,"parentId"=>3],"feature-phones"=>["id"=>66,"parentId"=>3],"walkie-talkie-parts-accessories"=>["id"=>67,"parentId"=>3],"walkie-talkie"=>["id"=>68,"parentId"=>3],"household-appliances"=>["id"=>69,"parentId"=>12],"home-appliance-parts"=>["id"=>70,"parentId"=>12],"personal-care-appliances"=>["id"=>71,"parentId"=>12],"kitchen-appliances"=>["id"=>72,"parentId"=>12],"commercial-appliances"=>["id"=>73,"parentId"=>12],"major-appliances"=>["id"=>74,"parentId"=>12],"storage-devices"=>["id"=>75,"parentId"=>4],"desktops"=>["id"=>76,"parentId"=>4],"mini-PC"=>["id"=>77,"parentId"=>4],"computer-components"=>["id"=>78,"parentId"=>4],"mouse-keyboards"=>["id"=>79,"parentId"=>4],"laptops"=>["id"=>80,"parentId"=>4],"tablets"=>["id"=>81,"parentId"=>4],"computer-peripherals"=>["id"=>82,"parentId"=>4],"device-cleaners"=>["id"=>83,"parentId"=>4],"laptop-accessories"=>["id"=>84,"parentId"=>4],"servers"=>["id"=>85,"parentId"=>4],"computer-cables-connectors"=>["id"=>86,"parentId"=>4],"tablet-accessories"=>["id"=>87,"parentId"=>4],"office-electronics"=>["id"=>88,"parentId"=>4],"laptop-parts"=>["id"=>89,"parentId"=>4],"demo-board-accessories"=>["id"=>90,"parentId"=>4],"office-software"=>["id"=>91,"parentId"=>4],"networking"=>["id"=>92,"parentId"=>4],"industrial-computer-accessories"=>["id"=>93,"parentId"=>4],"tablet-parts"=>["id"=>94,"parentId"=>4],"electrical-equipments-supplies"=>["id"=>95,"parentId"=>21],"painting-supplies-wall-treatments"=>["id"=>96,"parentId"=>21],"building-supplies"=>["id"=>97,"parentId"=>21],"hardware"=>["id"=>98,"parentId"=>21],"family-intelligence-system"=>["id"=>99,"parentId"=>21],"bathroom-fixtures"=>["id"=>100,"parentId"=>21],"kitchen-fixtures"=>["id"=>101,"parentId"=>21],"home-appliances"=>["id"=>102,"parentId"=>21],"plumbing"=>["id"=>103,"parentId"=>21],"lights-lighting-home-improvement"=>["id"=>104,"parentId"=>21],"men-socks"=>["id"=>105,"parentId"=>31],"mens-underwear"=>["id"=>106,"parentId"=>31],"womens-sleepwears"=>["id"=>107,"parentId"=>31],"mens-sleep-lounge"=>["id"=>108,"parentId"=>31],"womens-socks-hosiery"=>["id"=>109,"parentId"=>31],"womens-panties"=>["id"=>110,"parentId"=>31],"womens-intimates"=>["id"=>111,"parentId"=>31],"garden-supplies"=>["id"=>112,"parentId"=>10],"home-decor"=>["id"=>113,"parentId"=>10],"festive-party-supplies"=>["id"=>114,"parentId"=>10],"household-merchandises"=>["id"=>115,"parentId"=>10],"artscrafts-sewing"=>["id"=>116,"parentId"=>10],"bathroom-products"=>["id"=>117,"parentId"=>10],"home-textile"=>["id"=>118,"parentId"=>10],"kitchendining-bar"=>["id"=>119,"parentId"=>10],"pet-products"=>["id"=>120,"parentId"=>10],"home-storage-organization"=>["id"=>121,"parentId"=>10],"household-cleaning"=>["id"=>122,"parentId"=>10],"sports-accessories"=>["id"=>123,"parentId"=>17],"swimming"=>["id"=>124,"parentId"=>17],"roller-skates-skateboards-scooters"=>["id"=>125,"parentId"=>17],"golf"=>["id"=>126,"parentId"=>17],"water-sports"=>["id"=>127,"parentId"=>17],"fishing"=>["id"=>128,"parentId"=>17],"sports-bags"=>["id"=>129,"parentId"=>17],"cycling"=>["id"=>130,"parentId"=>17],"bowling"=>["id"=>131,"parentId"=>17],"fitness-body-building"=>["id"=>132,"parentId"=>17],"shooting"=>["id"=>133,"parentId"=>17],"camping-hiking"=>["id"=>134,"parentId"=>17],"team-sports"=>["id"=>135,"parentId"=>17],"sneakers"=>["id"=>136,"parentId"=>17],"entertainment"=>["id"=>137,"parentId"=>17],"other-sports-entertainment"=>["id"=>138,"parentId"=>17],"horse-racing"=>["id"=>139,"parentId"=>17],"musical-instruments"=>["id"=>140,"parentId"=>17],"sports-clothing"=>["id"=>141,"parentId"=>17],"running"=>["id"=>142,"parentId"=>17],"racquet-sports"=>["id"=>143,"parentId"=>17],"skiing-snowboarding"=>["id"=>144,"parentId"=>17],"hunting"=>["id"=>145,"parentId"=>17],"paper"=>["id"=>146,"parentId"=>5],"books-magazines"=>["id"=>147,"parentId"=>5],"calendars-planners-cards"=>["id"=>148,"parentId"=>5],"school-educational-supplies"=>["id"=>149,"parentId"=>5],"desk-accessories-organizer"=>["id"=>150,"parentId"=>5],"stationery-sticker"=>["id"=>151,"parentId"=>5],"labels-indexes-stamps"=>["id"=>152,"parentId"=>5],"tapes-adhesives-fasteners"=>["id"=>153,"parentId"=>5],"art-supplies"=>["id"=>154,"parentId"=>5],"mail-shipping-supplies"=>["id"=>155,"parentId"=>5],"filing-products"=>["id"=>156,"parentId"=>5],"office-binding-supplies"=>["id"=>157,"parentId"=>5],"presentation-supplies"=>["id"=>158,"parentId"=>5],"writing-correction-supplies"=>["id"=>159,"parentId"=>5],"notebooks-writing-pads"=>["id"=>160,"parentId"=>5],"cutting-supplies"=>["id"=>161,"parentId"=>5],"stress-relief-toy"=>["id"=>162,"parentId"=>15],"stuffed-animals-plush"=>["id"=>163,"parentId"=>15],"building-construction-toys"=>["id"=>164,"parentId"=>15],"puzzles-games"=>["id"=>165,"parentId"=>15],"remote-control-toys"=>["id"=>166,"parentId"=>15],"outdoor-fun-sports"=>["id"=>167,"parentId"=>15],"pools-water-fun"=>["id"=>168,"parentId"=>15],"arts-crafts-diy-toys"=>["id"=>169,"parentId"=>15],"play-vehicles-models"=>["id"=>170,"parentId"=>15],"dolls-accessories"=>["id"=>171,"parentId"=>15],"diecasts-toy-vehicles"=>["id"=>172,"parentId"=>15],"electronic-toys"=>["id"=>173,"parentId"=>15],"popular-toys"=>["id"=>174,"parentId"=>15],"high-tech-toys"=>["id"=>175,"parentId"=>15],"ride-on-toys"=>["id"=>176,"parentId"=>15],"novelty-gag-toys"=>["id"=>177,"parentId"=>15],"baby-toddler-toys"=>["id"=>178,"parentId"=>15],"classic-toys"=>["id"=>179,"parentId"=>15],"hobby-collectibles"=>["id"=>180,"parentId"=>15],"kids-party"=>["id"=>181,"parentId"=>15],"action-toy-figures"=>["id"=>182,"parentId"=>15],"model-building"=>["id"=>183,"parentId"=>15],"pretend-play"=>["id"=>184,"parentId"=>15],"learning-education"=>["id"=>185,"parentId"=>15],"security-alarm"=>["id"=>186,"parentId"=>6],"workplace-safety-supplies"=>["id"=>187,"parentId"=>6],"self-defense-supplies"=>["id"=>188,"parentId"=>6],"transmission-cables"=>["id"=>189,"parentId"=>6],"emergency-kits"=>["id"=>190,"parentId"=>6],"building-automation"=>["id"=>191,"parentId"=>6],"fire-protection"=>["id"=>192,"parentId"=>6],"access-control"=>["id"=>193,"parentId"=>6],"IoT-sevices"=>["id"=>194,"parentId"=>6],"smart-card-system"=>["id"=>195,"parentId"=>6],"video-surveillance"=>["id"=>196,"parentId"=>6],"public-broadcasting"=>["id"=>197,"parentId"=>6],"security-inspection-device"=>["id"=>198,"parentId"=>6],"door-intercom"=>["id"=>199,"parentId"=>6],"safes"=>["id"=>200,"parentId"=>6],"roadway-safety"=>["id"=>201,"parentId"=>6],"lightning-protection"=>["id"=>202,"parentId"=>6],"car-lights"=>["id"=>203,"parentId"=>20],"car-wash-maintenance"=>["id"=>204,"parentId"=>20],"exterior-accessories"=>["id"=>205,"parentId"=>20],"car-repair-tools"=>["id"=>206,"parentId"=>20],"motorcycle-accessories-parts"=>["id"=>207,"parentId"=>20],"interior-accessories"=>["id"=>208,"parentId"=>20],
                "atvrvboat-other-vehicle"=>["id"=>209,"parentId"=>20],"car-electronics"=>["id"=>210,"parentId"=>20],"travel-roadway-product"=>["id"=>211,"parentId"=>20],"auto-replacement-parts"=>["id"=>212,"parentId"=>20],"lighting-accessories"=>["id"=>213,"parentId"=>24],"special-engineering-lighting"=>["id"=>214,"parentId"=>24],"novelty-lighting"=>["id"=>215,"parentId"=>24],"book-lights"=>["id"=>216,"parentId"=>24],"portable-lighting"=>["id"=>217,"parentId"=>24],"holiday-lighting"=>["id"=>218,"parentId"=>24],"under-cabinet-lights"=>["id"=>219,"parentId"=>24],"professional-lighting"=>["id"=>220,"parentId"=>24],"night-lights"=>["id"=>221,"parentId"=>24],"ceiling-lights-and-fans"=>["id"=>222,"parentId"=>24],"outdoor-lighting"=>["id"=>223,"parentId"=>24],"commercial-lighting"=>["id"=>224,"parentId"=>24],"vanity-lights"=>["id"=>225,"parentId"=>24],"lamps-shades"=>["id"=>226,"parentId"=>24],"light-bulbs"=>["id"=>227,"parentId"=>24],"LED-lamps"=>["id"=>228,"parentId"=>24],"LED-lighting"=>["id"=>229,"parentId"=>24],"VR-AR-devices"=>["id"=>230,"parentId"=>7],"360-video-cameras-accessories"=>["id"=>231,"parentId"=>7],"HIFI-devices"=>["id"=>232,"parentId"=>7],"camera-photo"=>["id"=>233,"parentId"=>7],"electronic-cigarettes"=>["id"=>234,"parentId"=>7],"speakers"=>["id"=>235,"parentId"=>7],"home-electronic-accessories"=>["id"=>236,"parentId"=>7],"robot"=>["id"=>237,"parentId"=>7],"accessories-parts"=>["id"=>238,"parentId"=>7],"smart-electronics"=>["id"=>239,"parentId"=>7],"sports-and-action-video-cameras"=>["id"=>240,"parentId"=>7],"power-source"=>["id"=>241,"parentId"=>7],"wearable-devices"=>["id"=>242,"parentId"=>7],"portable-audio-video"=>["id"=>243,"parentId"=>7],"earphones-headphones"=>["id"=>244,"parentId"=>7],"live-equipment"=>["id"=>245,"parentId"=>7],"video-games"=>["id"=>246,"parentId"=>7],"home-audio-video"=>["id"=>247,"parentId"=>7],"sanitary-paper"=>["id"=>248,"parentId"=>18],"shaving-hair-removal"=>["id"=>249,"parentId"=>18],"hair-care-styling"=>["id"=>250,"parentId"=>18],"tools-accessories"=>["id"=>251,"parentId"=>18],"oral-hygiene"=>["id"=>252,"parentId"=>18],"beauty-equipment"=>["id"=>253,"parentId"=>18],"fragrances-deodorants"=>["id"=>254,"parentId"=>18],"nails-art-tools"=>["id"=>255,"parentId"=>18],"skin-care"=>["id"=>256,"parentId"=>18],"health-care"=>["id"=>257,"parentId"=>18],"tattoo-body-art"=>["id"=>258,"parentId"=>18],"mens-grooming"=>["id"=>259,"parentId"=>18],"makeup"=>["id"=>260,"parentId"=>18],"bath-shower"=>["id"=>261,"parentId"=>18],"sex-products"=>["id"=>262,"parentId"=>18],"skin-care-tools"=>["id"=>263,"parentId"=>18],"mens-vulcanize-shoes"=>["id"=>264,"parentId"=>14],"mens-casual-shoes"=>["id"=>265,"parentId"=>14],"womens-shoes"=>["id"=>266,"parentId"=>14],"womens-boots"=>["id"=>267,"parentId"=>14],"womens-vulcanize-shoes"=>["id"=>268,"parentId"=>14],"mens-shoes"=>["id"=>269,"parentId"=>14],"womens-flats"=>["id"=>270,"parentId"=>14],"mens-boots"=>["id"=>271,"parentId"=>14],"womens-pumps"=>["id"=>272,"parentId"=>14],"shoe-accessories"=>["id"=>273,"parentId"=>14],"electronic-data-systems"=>["id"=>274,"parentId"=>25],"electronic-accessories-supplies"=>["id"=>275,"parentId"=>25],"electronic-signs"=>["id"=>276,"parentId"=>25],"electronics-stocks"=>["id"=>277,"parentId"=>25],"optoelectronic-displays"=>["id"=>278,"parentId"=>25],"el-products"=>["id"=>279,"parentId"=>25],"active-components"=>["id"=>280,"parentId"=>25],"passive-components"=>["id"=>281,"parentId"=>25],"electronics-production-machinery"=>["id"=>282,"parentId"=>25],"other-electronic-components"=>["id"=>283,"parentId"=>25],"power-tools"=>["id"=>284,"parentId"=>22],"welding-equipment"=>["id"=>285,"parentId"=>22],"hand-power-tool-accessories"=>["id"=>286,"parentId"=>22],"construction-tools"=>["id"=>287,"parentId"=>22],"machine-tools-accessories"=>["id"=>288,"parentId"=>22],"welding-soldering-supplies"=>["id"=>289,"parentId"=>22],"measurement-analysis-instruments"=>["id"=>290,"parentId"=>22],"lifting-tools-accessories"=>["id"=>291,"parentId"=>22],"garden-tools"=>["id"=>292,"parentId"=>22],"abrasives"=>["id"=>293,"parentId"=>22],"abrasive-Tools"=>["id"=>294,"parentId"=>22],"hand-tools"=>["id"=>295,"parentId"=>22],"tool-sets"=>["id"=>296,"parentId"=>22],"woodworking-machinery-parts"=>["id"=>297,"parentId"=>22],"riveter-guns"=>["id"=>298,"parentId"=>22],"tool-organizers"=>["id"=>299,"parentId"=>22],"tool-parts"=>["id"=>300,"parentId"=>22],"cafe-furniture"=>["id"=>301,"parentId"=>26],"office-furniture"=>["id"=>302,"parentId"=>26],"bar-furniture"=>["id"=>303,"parentId"=>26],"furniture-parts"=>["id"=>304,"parentId"=>26],"commercial-furniture"=>["id"=>305,"parentId"=>26],"furniture-accessories"=>["id"=>306,"parentId"=>26],"outdoor-furniture"=>["id"=>307,"parentId"=>26],"children-furniture"=>["id"=>308,"parentId"=>26],"home-furniture"=>["id"=>309,"parentId"=>26],"beads-jewelry-making"=>["id"=>310,"parentId"=>8],"wedding-engagement-jewelry"=>["id"=>311,"parentId"=>8],"jewelry-sets-more"=>["id"=>312,"parentId"=>8],"jewelry-making"=>["id"=>313,"parentId"=>8],"earrings"=>["id"=>314,"parentId"=>8],"rings"=>["id"=>315,"parentId"=>8],"customized-jewelry"=>["id"=>316,"parentId"=>8],"necklaces-pendants"=>["id"=>317,"parentId"=>8],"fine-jewelry"=>["id"=>318,"parentId"=>8],"bracelets-bangles"=>["id"=>319,"parentId"=>8],"coin-purses-holders"=>["id"=>320,"parentId"=>13],"wallets"=>["id"=>321,"parentId"=>13],"womens-bags"=>["id"=>322,"parentId"=>13],"luggage-travel-bags"=>["id"=>323,"parentId"=>13],"functional-bags"=>["id"=>324,"parentId"=>13],"bag-parts-accessories"=>["id"=>325,"parentId"=>13],"kids-babys-bags"=>["id"=>326,"parentId"=>13],"backpacks"=>["id"=>327,"parentId"=>13],"mens-bags"=>["id"=>328,"parentId"=>13],"human-hair-weaves"=>["id"=>329,"parentId"=>19],"salon-hair-supply-chain"=>["id"=>330,"parentId"=>19],"lace-wigs"=>["id"=>331,"parentId"=>19],"synthetic-extensions"=>["id"=>332,"parentId"=>19],"hair-extensions"=>["id"=>333,"parentId"=>19],"hair-pieces"=>["id"=>334,"parentId"=>19],"hair-braids"=>["id"=>335,"parentId"=>19],"DIY-wigs"=>["id"=>336,"parentId"=>19],"synthetic-wigs"=>["id"=>337,"parentId"=>19],"hair-salon-tools-accessories"=>["id"=>338,"parentId"=>19],"prepaid-digital-code"=>["id"=>339,"parentId"=>27],"software-and-games"=>["id"=>340,"parentId"=>27],"tickets"=>["id"=>341,"parentId"=>27],"cartao-de-presente"=>["id"=>342,"parentId"=>27],"coupons"=>["id"=>343,"parentId"=>27],"work-wear-uniforms"=>["id"=>344,"parentId"=>28],"stage-dance-wear"=>["id"=>345,"parentId"=>28],"exotic-apparel"=>["id"=>346,"parentId"=>28],"costumes-accessories"=>["id"=>347,"parentId"=>28],"traditional-cultural-wear"=>["id"=>348,"parentId"=>28],"dresses-under-80-doller"=>["id"=>349,"parentId"=>29],"homecoming-dresses"=>["id"=>350,"parentId"=>29],"cocktail-dresses"=>["id"=>351,"parentId"=>29],"bridesmaid-dresses"=>["id"=>352,"parentId"=>29],"celebrity-Inspired-dresses"=>["id"=>353,"parentId"=>29],"evening-dresses"=>["id"=>354,"parentId"=>29],"mother-of-the-bride-dresses"=>["id"=>355,"parentId"=>29],"wedding-party-dress"=>["id"=>356,"parentId"=>29],"prom-dresses"=>["id"=>357,"parentId"=>29],"quinceanera-dresses"=>["id"=>358,"parentId"=>29],"wedding-dresses"=>["id"=>359,"parentId"=>29],"wedding-accessories"=>["id"=>360,"parentId"=>29],"womens-hair-accessories"=>["id"=>361,"parentId"=>30],"womens-glasses"=>["id"=>362,"parentId"=>30],"mens-gloves"=>["id"=>363,"parentId"=>30],"mens-belts"=>["id"=>364,"parentId"=>30],"boys-accessories"=>["id"=>365,"parentId"=>30],"womens-belts"=>["id"=>366,"parentId"=>30],"womens-gloves"=>["id"=>367,"parentId"=>30],"mens-scarves"=>["id"=>368,"parentId"=>30],"mens-accessories"=>["id"=>369,"parentId"=>30],"womens-hats"=>["id"=>370,"parentId"=>30],"womens-accessories"=>["id"=>371,"parentId"=>30],"mens-hats"=>["id"=>372,"parentId"=>30],"girls-accessories"=>["id"=>373,"parentId"=>30],"womens-scarves"=>["id"=>374,"parentId"=>30],"mens-glasses"=>["id"=>375,"parentId"=>30],"mens-ties-handkerchiefs"=>["id"=>376,"parentId"=>30],"garment-fabrics-accessories"=>["id"=>377,"parentId"=>30],"grain-products"=>["id"=>378,"parentId"=>23],"fish-and-sea-food"=>["id"=>379,"parentId"=>23],"ready-meal"=>["id"=>380,"parentId"=>23],"fruits-and-berries"=>["id"=>381,"parentId"=>23],"coffee"=>["id"=>382,"parentId"=>23],"grocery"=>["id"=>383,"parentId"=>23],"meat"=>["id"=>384,"parentId"=>23],"bread-and-pastries"=>["id"=>385,"parentId"=>23],"sausages"=>["id"=>386,"parentId"=>23],"nut-and-kernel"=>["id"=>387,"parentId"=>23],"canned-food"=>["id"=>388,"parentId"=>23],"milk-and-eggs"=>["id"=>389,"parentId"=>23],"fozen-products"=>["id"=>390,"parentId"=>23],"tea"=>["id"=>391,"parentId"=>23],"water-Juices-drinks"=>["id"=>392,"parentId"=>23],"alcoholic-beverages"=>["id"=>393,"parentId"=>23],"cheese"=>["id"=>394,"parentId"=>23],"vegetables-and-greens"=>["id"=>395,"parentId"=>23],"others"=>["id"=>396,"parentId"=>32],"food-others"=>["id"=>397,"parentId"=>23],"women-clothing-others"=>["id"=>398,"parentId"=>1],"men-clothing-others"=>["id"=>399,"parentId"=>2],"cellphones-others"=>["id"=>400,"parentId"=>3],"computer-others"=>["id"=>401,"parentId"=>4],"education-others"=>["id"=>402,"parentId"=>5],"security-others"=>["id"=>403,"parentId"=>6],"consumer-others"=>["id"=>404,"parentId"=>7],"jewelry-others"=>["id"=>405,"parentId"=>8],"watche-others"=>["id"=>406,"parentId"=>9],"home-others"=>["id"=>407,"parentId"=>10],"pet-others"=>["id"=>408,"parentId"=>11],"appliance-others"=>["id"=>409,"parentId"=>12],"luggage-others"=>["id"=>410,"parentId"=>13],"shoes-others"=>["id"=>411,"parentId"=>14],"toy-others"=>["id"=>412,"parentId"=>15],"mother-others"=>["id"=>413,"parentId"=>16],
                "sports-others"=>["id"=>414,"parentId"=>17],"beauty-others"=>["id"=>415,"parentId"=>18],"hair-others"=>["id"=>416,"parentId"=>19],"automobiles-others"=>["id"=>417,"parentId"=>20],"improvement-others"=>["id"=>418,"parentId"=>21],"tools-others"=>["id"=>419,"parentId"=>22],"light-others"=>["id"=>420,"parentId"=>24],"electronic-others"=>["id"=>421,"parentId"=>25],"furniture-others"=>["id"=>422,"parentId"=>26],"virtual-others"=>["id"=>423,"parentId"=>27],"novelty-others"=>["id"=>424,"parentId"=>28],"wedding-others"=>["id"=>425,"parentId"=>29],"apparel-others"=>["id"=>426,"parentId"=>30],"underwear-others"=>["id"=>427,"parentId"=>31]
            ];

            [
                'mens-clothing' =>
                    [
                        "id"        => 200,
                        "parentId"  => 3,
                    ],
                'womens-Clothing' =>
                    [
                        "id"        => 201,
                        "parentId"  => 2,
                    ],
            ];
        }   

    /*
    |-------------------------------------------------------------------------------------------------------
    | END - Ebaskat all sub-category for bigbuy sub-category match with ebaskat sub-category
    |-------------------------------------------------------------------------------------------------------
    */



    /*
    |-------------------------------------------------------------------------------------------------------
    | Ebaskat all restricted key word for bigbuy and others (restricted product)
    |-------------------------------------------------------------------------------------------------------
    */
        function restrictedKeyWord_hh()
        {
           return [
               'e-cigarettes','e-liquid','pharmacies','pharmaceuticals','prescription',
               'peptides and research chemicals','peptides','chemicals','research chemicals','fake references or ID-providing services',
               'fake references','ID-providing services','age restricted goods','age restricted services',
               'age restricted goods or services','gunpowder','explosives','fireworks','fireworks goods',
               'weapons and munitions','gunpowder and other explosives','fireworks and related goods',' toxic',
               'flammable','radioactive materials',
                'cannabis','cannabidiol','tobacco','marijuana'
            ]; 
        }
        function restrictedKeyWordExistOrNot_hh($title,$description)
        {
            $matchRestrictedKeyWord = 0;
            foreach(restrictedKeyWord_hh() as $restrictedKeyWord)
            {
                //if(preg_match('/\b' . preg_quote($title, '/') . '\b/i', $restrictedKeyWord, $matches))
                if(preg_match("/$restrictedKeyWord/", $title))
                {
                    $matchRestrictedKeyWord = 1;
                    break; 
                }
                //else if(preg_match('/\b' . preg_quote($description, '/') . '\b/i', $restrictedKeyWord, $matches))
                else if(preg_match("/$restrictedKeyWord/", $description))
                {
                    $matchRestrictedKeyWord = 1; 
                    break; 
                }
                else{
                    $matchRestrictedKeyWord = 0; 
                }
            }
            return $matchRestrictedKeyWord;
        }

    /*
    |-------------------------------------------------------------------------------------------------------
    | END  Ebaskat all restricted key word for bigbuy and others (restricted product)
    |-------------------------------------------------------------------------------------------------------
    */



    
    /*
    |-------------------------------------------------------------------------------------------------------
    | orders => Order delivery status : main order status
    |-------------------------------------------------------------------------------------------------------
    */
       function main_orders_status_hh()
       {
           return [
               //value  =>  label 
               'pending' => 'Pending',
               'processing' => 'Processing',
               'on delivery' => 'On Delivery',
               'partial delivered' => 'Partial Delivered',
               'completed' => 'Completed',
               'declined' => 'Declined',
               'partial_refund' => 'Partial Refund',
               'refunded' => 'Refunded'
           ];
       }
    /*
    |-------------------------------------------------------------------------------------------------------
    | orders => Order delivery status : main order status
    |-------------------------------------------------------------------------------------------------------
    */

    /*
    |-------------------------------------------------------------------------------------------------------
    | display main order delivery status label with color (class)
    |-------------------------------------------------------------------------------------------------------
    */
        function main_orders_status_label_and_class_hh($statusKey)
        {
            $status = [];
            $status['label'] = "";
            $status['class'] = "";
            $label = main_orders_status_hh()[$statusKey];
            switch ($statusKey) {
                    case ( $statusKey == "pending" ):
                        $status = [
                            "label" => $label,
                            "class" => "warning"
                        ];
                        break;
                    case ( $statusKey == "processing" ):
                        $status = [
                            "label" => $label,
                            "class" => "secondary"
                        ];
                        break;
                    case ( $statusKey == "on delivery"):
                        $status = [
                            "label" => $label,
                            "class" => "info"
                        ];
                        break;
                    case ( $statusKey == "partial delivered" ):
                        $status = [
                            "label" => $label,
                            "class" => "primary"
                        ];
                        break;
                    case ( $statusKey == "completed" ):
                            $status = [
                                "label" => $label,
                                "class" => "success"
                            ];
                        break;
                    case ( $statusKey == "declined" ):
                            $status = [
                                "label" => $label,
                                "class" => "danger"
                            ];
                        break;
                    case ( $statusKey == "partial_refund" ):
                            $status = [
                                "label" => $label,
                                "class" => "dark"
                            ];
                        break;
                    case ( $statusKey == "refunded" ):
                            $status = [
                                "label" => $label,
                                "class" => "danger"
                            ];
                        break;
                    default:
                        $status = [
                            "label" => $label,
                            "class" => "danger"
                        ];
                        break;
                } 
            return $status;
        }
    /*
    |-------------------------------------------------------------------------------------------------------
    | display main order delivery status label with color (class)
    |-------------------------------------------------------------------------------------------------------
    */

    
    /*
    |-------------------------------------------------------------------------------------------------------
    | making package order status from dropshipping status
    |-------------------------------------------------------------------------------------------------------
    */
        function makingBigbuyPackageOrderStatusBasedOnDsStatus($dsStatus)
        {
            $status = "pending";
            $dsStatus = strtolower($dsStatus);
            switch ($dsStatus) {
                case ( $dsStatus == "pending invoicing" || $dsStatus == "pending payment"):
                    $status = "pending"; 
                    break;
                case ( $dsStatus == "partially shipped" || $dsStatus == "partially shipped" 
                        || $dsStatus == "processed" || $dsStatus == "processing" 
                        || $dsStatus == "paypal validation pending" || $dsStatus == "payment incident" 
                        || $dsStatus == "flexible payment" || $dsStatus == "ready to ship" 
                        || $dsStatus == "delivery incidents" || $dsStatus == "other incidents" 
                        || $dsStatus == "in transit" 
                    ):
                    $status = "processing"; 
                    break;
                case ( $dsStatus == "out for delivery" || $dsStatus == "shipped"):
                    $status = "on delivery"; 
                    break;
                case ( $dsStatus == "delivered" ):
                    $status = "completed"; 
                    break;
                case ( $dsStatus == "cancelled" || $dsStatus ==  "Reimbursed payment" ||  $dsStatus == "return"):
                    $status = "declined"; 
                    break;
                default:
                $status = "pending"; 
                break;
            } 
            return $status;
        }
    /*
    |-------------------------------------------------------------------------------------------------------
    | making package order status from dropshipping status
    |-------------------------------------------------------------------------------------------------------
    */

    
    /*
    |-------------------------------------------------------------------------------------------------------
    | making package order status from dropshipping status
    |-------------------------------------------------------------------------------------------------------
    */
        function makingBigbuyPackageStatusBasedOnDsStatus($dsStatus)
        {
            $status = "pending";
            $dsStatus = strtolower($dsStatus);
            switch ($dsStatus) {
                case ( $dsStatus == "pending invoicing" || $dsStatus == "pending payment"):
                    $status = "pending"; 
                    break;
                case ( $dsStatus == "partially shipped" || $dsStatus == "partially shipped" 
                        || $dsStatus == "processed" || $dsStatus == "processing" 
                        || $dsStatus == "paypal validation pending" || $dsStatus == "payment incident" 
                        || $dsStatus == "flexible payment" || $dsStatus == "ready to ship" 
                        || $dsStatus == "delivery incidents" || $dsStatus == "other incidents" 
                        || $dsStatus == "in transit" 
                    ):
                    $status = "processing"; 
                    break;
                case ( $dsStatus == "out for delivery" || $dsStatus == "shipped"):
                    $status = "on delivery"; 
                    break;
                case ( $dsStatus == "delivered" ):
                    $status = "completed"; 
                    break;
                case ( $dsStatus == "cancelled" || $dsStatus ==  "Reimbursed payment" ||  $dsStatus == "return"):
                    $status = "declined"; 
                    break;
                /* case ( $dsStatus == "partial delivered" ):
                    $status = "partial delivered"; 
                    break;
                case ( $dsStatus == "partial_refund" ):
                    $status = "partial_refund"; 
                    break;
                case ( $dsStatus == "refunded" ):
                    $status = "refunded"; 
                    break; */
                default:
                $status = "pending"; 
                break;
            } 
            return $status;
        }
    /*
    |-------------------------------------------------------------------------------------------------------
    | making package order status from dropshipping status
    |-------------------------------------------------------------------------------------------------------
    */


    /*
    |-------------------------------------------------------------------------------------------------------
    | order_packages => Order delivery status : order packages status
    |-------------------------------------------------------------------------------------------------------
    */
       function order_packages_status_hh()
       {
           return [
               //value  =>  label 
               'pending' => 'Pending',
               'processing' => 'Processing',
               'on delivery' => 'On Delivery',
               'partial delivered' => 'Partial Delivered',
               'completed' => 'Completed',
               'declined' => 'Declined'
           ];
       }
    /*
    |-------------------------------------------------------------------------------------------------------
    | order_packages => Order delivery status : order packages status
    |-------------------------------------------------------------------------------------------------------
    */



    /*
    |-------------------------------------------------------------------------------------------------------
    | order_products => Order delivery status : order products status
    |-------------------------------------------------------------------------------------------------------
    */
       function order_products_status_hh()
       {
           return [
               //value  =>  label 
               'pending' => 'Pending',
               'processing' => 'Processing',
               'on delivery' => 'On Delivery',
               'completed' => 'Completed',
               'declined' => 'Declined'
           ];
       }
    /*
    |-------------------------------------------------------------------------------------------------------
    | order_products => Order delivery status : order products status
    |-------------------------------------------------------------------------------------------------------
    */





    /*
    |-------------------------------------------------------------------------------------------------------
    | bigbuy order placement based on order products table (order_products) 
    |-------------------------------------------------------------------------------------------------------
    */
        function bigbuy_order_placement_hh($orderPackage)
        {
            $dsOrderPlacedOrNot     = [];
            $dsOrderPlacedCount     = 0;
            $dsOrderNotPlacedCount  = 0;
            foreach($orderPackage->orderProducts as $i => $value)
            {
                if($value->ds_order_no)
                {
                    //array_push($dsOrderPlacedOrNot,'order_placed','oplaced_'.$i);
                    $dsOrderPlacedCount++;
                }else{
                    //array_push($dsOrderPlacedOrNot,'order_not_placed','not_placed_'.$i);
                    $dsOrderNotPlacedCount++;
                }
            } 
            $totalItem  = $dsOrderPlacedCount + $dsOrderNotPlacedCount;
            $status = "";
            if($totalItem == $dsOrderPlacedCount)
            {
                //"full order placed";
                $status = 1;
            }
            else if($totalItem == $dsOrderNotPlacedCount)
            {
                //"order not placed";
                $status = 0;
            } 
            else if(($dsOrderPlacedCount > 0 ) && ($totalItem > $dsOrderPlacedCount))
            {
                //"order partianl placed";
                $status = 2;
            }
            return $status;
            /*
                echo "<pre>";
                print_r($dsOrderPlacedOrNot);
                echo "</pre>";
                echo "<br/>";
                echo "dsOrderPlacedCount: " . $dsOrderPlacedCount; echo "<br/>";
                echo "dsOrderNotPlacedCount: " . $dsOrderNotPlacedCount; 
            */
        }

        function bigbuy_order_placement_status_hh($statusNo)
        {
            if($statusNo == 1)
            {
                $status = '<span style="padding:0px 3px;background-color:green;color:#ffff;">Full Placed</span>';
                //echo "order placed";
            }
            else if($statusNo == 0)
            {
                $status = '<span style="padding:0px 2px;background-color:red;color:#ffff;">Not Placed</span>';
                //echo "order not placed";
            } 
            else if($statusNo == 2)
            {
                $status = '<small style="padding:0px 2px;background-color:blue;color:#ffff;">Partial Placed</small>';
                //echo "order partianl placed";
            }
            echo $status;
            return;
            /* 
                if($totalItem == $dsOrderPlacedCount)
                {
                    $status = '<span style="padding:0px 3px;background-color:green;color:#ffff;">Full Placed</span>';
                    //echo "order placed";
                }
                else if($totalItem == $dsOrderNotPlacedCount)
                {
                    $status = '<span style="padding:0px 2px;background-color:red;color:#ffff;">Not Placed</span>';
                    //echo "order not placed";
                } 
                else if(($dsOrderPlacedCount > 0 ) && ($totalItem > $dsOrderPlacedCount))
                {
                    $status = '<small style="padding:0px 2px;background-color:blue;color:#ffff;">Partial Placed</small>';
                    //echo "order partianl placed";
                }
                echo $status;
                return; 
            */
        }
    /*
    |-------------------------------------------------------------------------------------------------------
    | bigbuy order placement based on order products table (order_products) 
    |-------------------------------------------------------------------------------------------------------
    */








