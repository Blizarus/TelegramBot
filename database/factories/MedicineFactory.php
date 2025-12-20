<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Medicine>
 */
class MedicineFactory extends Factory
{
    protected $russianMedicines = [
        [
            'trade_name' => 'Аспирин',
            'inn' => 'Ацетилсалициловая кислота',
            'dosage_form' => 'таблетки',
            'dosage' => '500 мг',
            'pack_size' => '10 таблеток',
            'manufacturer' => 'Байер',
            'country' => 'Россия',
            'description' => 'Нестероидный противовоспалительный препарат, антиагрегант',
            'atc_code' => 'N02BA01',
            'image_url' => 'https://example.com/aspirin.jpg'
        ],
        [
            'trade_name' => 'Анальгин',
            'inn' => 'Метамизол натрия',
            'dosage_form' => 'таблетки',
            'dosage' => '500 мг',
            'pack_size' => '20 таблеток',
            'manufacturer' => 'Фармстандарт',
            'country' => 'Россия',
            'description' => 'Анальгетик-антипиретик',
            'atc_code' => 'N02BB02',
            'image_url' => 'https://example.com/analgin.jpg'
        ],
        [
            'trade_name' => 'Парацетамол',
            'inn' => 'Парацетамол',
            'dosage_form' => 'таблетки',
            'dosage' => '500 мг',
            'pack_size' => '20 таблеток',
            'manufacturer' => 'Оболенское',
            'country' => 'Россия',
            'description' => 'Жаропонижающее и обезболивающее средство',
            'atc_code' => 'N02BE01',
            'image_url' => 'https://example.com/paracetamol.jpg'
        ],
        [
            'trade_name' => 'Нурофен',
            'inn' => 'Ибупрофен',
            'dosage_form' => 'таблетки',
            'dosage' => '200 мг',
            'pack_size' => '20 таблеток',
            'manufacturer' => 'Рекитт Бенкизер',
            'country' => 'Россия',
            'description' => 'Противовоспалительное, жаропонижающее, анальгетик',
            'atc_code' => 'M01AE01',
            'image_url' => 'https://example.com/nurofen.jpg'
        ],
        [
            'trade_name' => 'Цитрамон',
            'inn' => 'Кофеин + Парацетамол + Ацетилсалициловая кислота',
            'dosage_form' => 'таблетки',
            'dosage' => '30+240+240 мг',
            'pack_size' => '10 таблеток',
            'manufacturer' => 'Обновление',
            'country' => 'Россия',
            'description' => 'Комбинированный анальгетик при головной боли',
            'atc_code' => 'N02BA71',
            'image_url' => 'https://example.com/citramon.jpg'
        ],
        [
            'trade_name' => 'Кеторол',
            'inn' => 'Кеторолак',
            'dosage_form' => 'таблетки',
            'dosage' => '10 мг',
            'pack_size' => '20 таблеток',
            'manufacturer' => 'Д-р Реддис',
            'country' => 'Россия',
            'description' => 'Сильное обезболивающее средство',
            'atc_code' => 'M01AB15',
            'image_url' => 'https://example.com/ketorol.jpg'
        ],
        [
            'trade_name' => 'Эреспал',
            'inn' => 'Фенспирид',
            'dosage_form' => 'сироп',
            'dosage' => '2 мг/мл',
            'pack_size' => '150 мл',
            'manufacturer' => 'Сервье',
            'country' => 'Россия',
            'description' => 'Противовоспалительное средство при заболеваниях дыхательных путей',
            'atc_code' => 'R03DX03',
            'image_url' => 'https://example.com/erespal.jpg'
        ],
        [
            'trade_name' => 'Амоксиклав',
            'inn' => 'Амоксициллин + Клавулановая кислота',
            'dosage_form' => 'таблетки',
            'dosage' => '500+125 мг',
            'pack_size' => '14 таблеток',
            'manufacturer' => 'Лек',
            'country' => 'Словения',
            'description' => 'Антибиотик широкого спектра действия',
            'atc_code' => 'J01CR02',
            'image_url' => 'https://example.com/amoksiklav.jpg'
        ],
        [
            'trade_name' => 'Азитромицин',
            'inn' => 'Азитромицин',
            'dosage_form' => 'капсулы',
            'dosage' => '500 мг',
            'pack_size' => '3 капсулы',
            'manufacturer' => 'Вертекс',
            'country' => 'Россия',
            'description' => 'Антибиотик группы макролидов',
            'atc_code' => 'J01FA10',
            'image_url' => 'https://example.com/azitromicin.jpg'
        ],
        [
            'trade_name' => 'Сумамед',
            'inn' => 'Азитромицин',
            'dosage_form' => 'таблетки',
            'dosage' => '500 мг',
            'pack_size' => '3 таблетки',
            'manufacturer' => 'Плива',
            'country' => 'Хорватия',
            'description' => 'Антибиотик широкого спектра действия',
            'atc_code' => 'J01FA10',
            'image_url' => 'https://example.com/sumamed.jpg'
        ],
        [
            'trade_name' => 'Левомицетин',
            'inn' => 'Хлорамфеникол',
            'dosage_form' => 'таблетки',
            'dosage' => '500 мг',
            'pack_size' => '10 таблеток',
            'manufacturer' => 'Биосинтез',
            'country' => 'Россия',
            'description' => 'Антибиотик широкого спектра действия',
            'atc_code' => 'J01BA01',
            'image_url' => 'https://example.com/levomicetin.jpg'
        ],
        [
            'trade_name' => 'Терафлю',
            'inn' => 'Парацетамол + Фенилэфрин + Фенирамин',
            'dosage_form' => 'порошок',
            'dosage' => '325+10+20 мг',
            'pack_size' => '10 пакетиков',
            'manufacturer' => 'Новартис',
            'country' => 'Швейцария',
            'description' => 'Комбинированное средство для лечения гриппа и простуды',
            'atc_code' => 'R05X',
            'image_url' => 'https://example.com/teraflu.jpg'
        ],
        [
            'trade_name' => 'Арбидол',
            'inn' => 'Умифеновир',
            'dosage_form' => 'капсулы',
            'dosage' => '100 мг',
            'pack_size' => '10 капсул',
            'manufacturer' => 'Фармстандарт',
            'country' => 'Россия',
            'description' => 'Противовирусный препарат',
            'atc_code' => 'J05AX13',
            'image_url' => 'https://example.com/arbidol.jpg'
        ],
        [
            'trade_name' => 'Кагоцел',
            'inn' => 'Кагоцел',
            'dosage_form' => 'таблетки',
            'dosage' => '12 мг',
            'pack_size' => '10 таблеток',
            'manufacturer' => 'Ниармедик Фарма',
            'country' => 'Россия',
            'description' => 'Противовирусный и иммуномодулирующий препарат',
            'atc_code' => 'J05AX',
            'image_url' => 'https://example.com/kagocel.jpg'
        ],
        [
            'trade_name' => 'Эргоферон',
            'inn' => 'Антитела к гистамину + Антитела к CD4',
            'dosage_form' => 'таблетки',
            'dosage' => '0,006 г',
            'pack_size' => '20 таблеток',
            'manufacturer' => 'Материа Медика',
            'country' => 'Россия',
            'description' => 'Противовирусный и антигистаминный препарат',
            'atc_code' => 'J05AX',
            'image_url' => 'https://example.com/ergoferon.jpg'
        ],
        [
            'trade_name' => 'Анаферон',
            'inn' => 'Антитела к гамма-интерферону',
            'dosage_form' => 'таблетки',
            'dosage' => '0,003 г',
            'pack_size' => '20 таблеток',
            'manufacturer' => 'Материа Медика',
            'country' => 'Россия',
            'description' => 'Противовирусный и иммуномодулирующий препарат',
            'atc_code' => 'L03AX',
            'image_url' => 'https://example.com/anaferon.jpg'
        ],
        [
            'trade_name' => 'Оциллококцинум',
            'inn' => 'Анас барбариэлиум',
            'dosage_form' => 'гранулы',
            'dosage' => '0,01 мл',
            'pack_size' => '6 доз',
            'manufacturer' => 'Буарон',
            'country' => 'Франция',
            'description' => 'Гомеопатическое средство при гриппе и ОРВИ',
            'atc_code' => 'V03AX',
            'image_url' => 'https://example.com/oscillococcinum.jpg'
        ],
        [
            'trade_name' => 'Називин',
            'inn' => 'Оксиметазолин',
            'dosage_form' => 'спрей назальный',
            'dosage' => '0,05%',
            'pack_size' => '10 мл',
            'manufacturer' => 'Мерк',
            'country' => 'Германия',
            'description' => 'Сосудосуживающее средство при рините',
            'atc_code' => 'R01AA05',
            'image_url' => 'https://example.com/nazivin.jpg'
        ],
        [
            'trade_name' => 'Нафтизин',
            'inn' => 'Нафазолин',
            'dosage_form' => 'капли назальные',
            'dosage' => '0,1%',
            'pack_size' => '10 мл',
            'manufacturer' => 'Обновление',
            'country' => 'Россия',
            'description' => 'Сосудосуживающее средство при рините',
            'atc_code' => 'R01AA08',
            'image_url' => 'https://example.com/naftizin.jpg'
        ],
        [
            'trade_name' => 'Аква Марис',
            'inn' => 'Морская вода',
            'dosage_form' => 'спрей назальный',
            'dosage' => '30 мл',
            'pack_size' => '1 флакон',
            'manufacturer' => 'Ядран',
            'country' => 'Хорватия',
            'description' => 'Средство для промывания и орошения полости носа',
            'atc_code' => 'R01AX10',
            'image_url' => 'https://example.com/aquamaris.jpg'
        ],
        [
            'trade_name' => 'Стрепсилс',
            'inn' => 'Амилметакрезол + Дихлорбензиловый спирт',
            'dosage_form' => 'пастилки',
            'dosage' => '0,6+1,2 мг',
            'pack_size' => '24 пастилки',
            'manufacturer' => 'Рекитт Бенкизер',
            'country' => 'Великобритания',
            'description' => 'Антисептическое средство при боли в горле',
            'atc_code' => 'R02AA20',
            'image_url' => 'https://example.com/strepsils.jpg'
        ],
        [
            'trade_name' => 'Фарингосепт',
            'inn' => 'Амбазон',
            'dosage_form' => 'таблетки для рассасывания',
            'dosage' => '10 мг',
            'pack_size' => '20 таблеток',
            'manufacturer' => 'А.О. Терапия',
            'country' => 'Румыния',
            'description' => 'Антисептическое средство при инфекциях ротоглотки',
            'atc_code' => 'R02AA03',
            'image_url' => 'https://example.com/faringosept.jpg'
        ],
        [
            'trade_name' => 'Лизобакт',
            'inn' => 'Лизоцим + Пиридоксин',
            'dosage_form' => 'таблетки для рассасывания',
            'dosage' => '20+10 мг',
            'pack_size' => '30 таблеток',
            'manufacturer' => 'Босналек',
            'country' => 'Босния и Герцеговина',
            'description' => 'Антисептик для лечения инфекций ротовой полости',
            'atc_code' => 'R02AA20',
            'image_url' => 'https://example.com/lizobakt.jpg'
        ],
        [
            'trade_name' => 'Амбробене',
            'inn' => 'Амброксол',
            'dosage_form' => 'сироп',
            'dosage' => '15 мг/5 мл',
            'pack_size' => '100 мл',
            'manufacturer' => 'Меркле',
            'country' => 'Германия',
            'description' => 'Муколитическое и отхаркивающее средство',
            'atc_code' => 'R05CB06',
            'image_url' => 'https://example.com/ambrobene.jpg'
        ],
        [
            'trade_name' => 'АЦЦ',
            'inn' => 'Ацетилцистеин',
            'dosage_form' => 'шипучие таблетки',
            'dosage' => '600 мг',
            'pack_size' => '10 таблеток',
            'manufacturer' => 'Гексал',
            'country' => 'Германия',
            'description' => 'Муколитическое средство',
            'atc_code' => 'R05CB01',
            'image_url' => 'https://example.com/acc.jpg'
        ],
        [
            'trade_name' => 'Лазолван',
            'inn' => 'Амброксол',
            'dosage_form' => 'сироп',
            'dosage' => '15 мг/5 мл',
            'pack_size' => '100 мл',
            'manufacturer' => 'Берингер Ингельхайм',
            'country' => 'Германия',
            'description' => 'Муколитическое и отхаркивающее средство',
            'atc_code' => 'R05CB06',
            'image_url' => 'https://example.com/lazolvan.jpg'
        ],
        [
            'trade_name' => 'Бромгексин',
            'inn' => 'Бромгексин',
            'dosage_form' => 'таблетки',
            'dosage' => '8 мг',
            'pack_size' => '50 таблеток',
            'manufacturer' => 'Обновление',
            'country' => 'Россия',
            'description' => 'Муколитическое и отхаркивающее средство',
            'atc_code' => 'R05CB02',
            'image_url' => 'https://example.com/bromgeksin.jpg'
        ],
        [
            'trade_name' => 'Но-шпа',
            'inn' => 'Дротаверин',
            'dosage_form' => 'таблетки',
            'dosage' => '40 мг',
            'pack_size' => '24 таблетки',
            'manufacturer' => 'Хиноин',
            'country' => 'Венгрия',
            'description' => 'Спазмолитическое средство',
            'atc_code' => 'A03AD02',
            'image_url' => 'https://example.com/noshpa.jpg'
        ],
        [
            'trade_name' => 'Спазмалгон',
            'inn' => 'Метамизол натрия + Питофенон + Фенпивериния бромид',
            'dosage_form' => 'таблетки',
            'dosage' => '500+5+0,1 мг',
            'pack_size' => '20 таблеток',
            'manufacturer' => 'Балканфарма',
            'country' => 'Болгария',
            'description' => 'Спазмолитическое и анальгетическое средство',
            'atc_code' => 'N02BB72',
            'image_url' => 'https://example.com/spazmalgon.jpg'
        ],
        [
            'trade_name' => 'Смекта',
            'inn' => 'Смектит диоктаэдрический',
            'dosage_form' => 'порошок',
            'dosage' => '3 г',
            'pack_size' => '10 пакетиков',
            'manufacturer' => 'Ипсен',
            'country' => 'Франция',
            'description' => 'Противодиарейное средство, адсорбент',
            'atc_code' => 'A07BC05',
            'image_url' => 'https://example.com/smekta.jpg'
        ],
        [
            'trade_name' => 'Энтерофурил',
            'inn' => 'Нифуроксазид',
            'dosage_form' => 'капсулы',
            'dosage' => '100 мг',
            'pack_size' => '16 капсул',
            'manufacturer' => 'Босналек',
            'country' => 'Босния и Герцеговина',
            'description' => 'Противодиарейное и кишечное противомикробное средство',
            'atc_code' => 'A07AX03',
            'image_url' => 'https://example.com/enterofuril.jpg'
        ],
        [
            'trade_name' => 'Имодиум',
            'inn' => 'Лоперамид',
            'dosage_form' => 'капсулы',
            'dosage' => '2 мг',
            'pack_size' => '10 капсул',
            'manufacturer' => 'Джонсон & Джонсон',
            'country' => 'США',
            'description' => 'Противодиарейное средство',
            'atc_code' => 'A07DA03',
            'image_url' => 'https://example.com/imodium.jpg'
        ],
        [
            'trade_name' => 'Мезим',
            'inn' => 'Панкреатин',
            'dosage_form' => 'таблетки',
            'dosage' => '10 000 ЕД',
            'pack_size' => '20 таблеток',
            'manufacturer' => 'Берлин-Хеми',
            'country' => 'Германия',
            'description' => 'Ферментное средство, улучшающее пищеварение',
            'atc_code' => 'A09AA02',
            'image_url' => 'https://example.com/mezim.jpg'
        ],
        [
            'trade_name' => 'Фестал',
            'inn' => 'Панкреатин + Гемицеллюлаза + Желчи компоненты',
            'dosage_form' => 'таблетки',
            'dosage' => '192+50+25 мг',
            'pack_size' => '20 таблеток',
            'manufacturer' => 'Санофи',
            'country' => 'Франция',
            'description' => 'Ферментное средство, улучшающее пищеварение',
            'atc_code' => 'A09AA02',
            'image_url' => 'https://example.com/festal.jpg'
        ],
        [
            'trade_name' => 'Креон',
            'inn' => 'Панкреатин',
            'dosage_form' => 'капсулы',
            'dosage' => '25 000 ЕД',
            'pack_size' => '20 капсул',
            'manufacturer' => 'Эбботт',
            'country' => 'Германия',
            'description' => 'Ферментное средство при недостаточности поджелудочной железы',
            'atc_code' => 'A09AA02',
            'image_url' => 'https://example.com/kreon.jpg'
        ],
        [
            'trade_name' => 'Линекс',
            'inn' => 'Лактобактерии + Бифидобактерии',
            'dosage_form' => 'капсулы',
            'dosage' => '280 мг',
            'pack_size' => '16 капсул',
            'manufacturer' => 'Лек',
            'country' => 'Словения',
            'description' => 'Пробиотик, нормализующий микрофлору кишечника',
            'atc_code' => 'A07FA',
            'image_url' => 'https://example.com/linex.jpg'
        ],
        [
            'trade_name' => 'Хилак форте',
            'inn' => 'Молочная кислота',
            'dosage_form' => 'капли',
            'dosage' => '100 мл',
            'pack_size' => '1 флакон',
            'manufacturer' => 'Меркле',
            'country' => 'Германия',
            'description' => 'Препарат, нормализующий микрофлору кишечника',
            'atc_code' => 'A07FA',
            'image_url' => 'https://example.com/hilak-forte.jpg'
        ],
        [
            'trade_name' => 'Активированный уголь',
            'inn' => 'Активированный уголь',
            'dosage_form' => 'таблетки',
            'dosage' => '250 мг',
            'pack_size' => '50 таблеток',
            'manufacturer' => 'Обновление',
            'country' => 'Россия',
            'description' => 'Энтеросорбент при отравлениях и интоксикациях',
            'atc_code' => 'A07BA01',
            'image_url' => 'https://example.com/activated-carbon.jpg'
        ],
        [
            'trade_name' => 'Полисорб',
            'inn' => 'Кремния диоксид коллоидный',
            'dosage_form' => 'порошок',
            'dosage' => '3 г',
            'pack_size' => '10 пакетиков',
            'manufacturer' => 'Полисорб',
            'country' => 'Россия',
            'description' => 'Энтеросорбент при отравлениях и аллергиях',
            'atc_code' => 'A07BC02',
            'image_url' => 'https://example.com/polisorb.jpg'
        ],
        [
            'trade_name' => 'Энтеросгель',
            'inn' => 'Полиметилсилоксана полигидрат',
            'dosage_form' => 'паста',
            'dosage' => '225 г',
            'pack_size' => '1 туба',
            'manufacturer' => 'ТНК Силма',
            'country' => 'Россия',
            'description' => 'Энтеросорбент при отравлениях и интоксикациях',
            'atc_code' => 'A07BC03',
            'image_url' => 'https://example.com/enterosgel.jpg'
        ],
        [
            'trade_name' => 'Афобазол',
            'inn' => 'Фабомотизол',
            'dosage_form' => 'таблетки',
            'dosage' => '10 мг',
            'pack_size' => '60 таблеток',
            'manufacturer' => 'Фармстандарт',
            'country' => 'Россия',
            'description' => 'Анксиолитическое средство (транквилизатор)',
            'atc_code' => 'N05BX04',
            'image_url' => 'https://example.com/afobazol.jpg'
        ],
        [
            'trade_name' => 'Тенотен',
            'inn' => 'Антитела к мозгоспецифическому белку S-100',
            'dosage_form' => 'таблетки',
            'dosage' => '0,003 г',
            'pack_size' => '40 таблеток',
            'manufacturer' => 'Материа Медика',
            'country' => 'Россия',
            'description' => 'Анксиолитическое и ноотропное средство',
            'atc_code' => 'N05BX',
            'image_url' => 'https://example.com/tenoten.jpg'
        ],
        [
            'trade_name' => 'Ново-Пассит',
            'inn' => 'Экстракт лекарственных растений',
            'dosage_form' => 'сироп',
            'dosage' => '200 мл',
            'pack_size' => '1 флакон',
            'manufacturer' => 'Тева',
            'country' => 'Израиль',
            'description' => 'Седативное средство растительного происхождения',
            'atc_code' => 'N05CM',
            'image_url' => 'https://example.com/novo-passit.jpg'
        ],
        [
            'trade_name' => 'Персен',
            'inn' => 'Экстракт валерианы + Мелиссы + Мяты',
            'dosage_form' => 'таблетки',
            'dosage' => '50+25+25 мг',
            'pack_size' => '40 таблеток',
            'manufacturer' => 'Сандоз',
            'country' => 'Швейцария',
            'description' => 'Седативное средство растительного происхождения',
            'atc_code' => 'N05CM',
            'image_url' => 'https://example.com/persen.jpg'
        ],
        [
            'trade_name' => 'Валерьянка',
            'inn' => 'Экстракт валерианы',
            'dosage_form' => 'таблетки',
            'dosage' => '20 мг',
            'pack_size' => '50 таблеток',
            'manufacturer' => 'Обновление',
            'country' => 'Россия',
            'description' => 'Седативное средство растительного происхождения',
            'atc_code' => 'N05CM09',
            'image_url' => 'https://example.com/valerianka.jpg'
        ],
        [
            'trade_name' => 'Драмина',
            'inn' => 'Дименгидринат',
            'dosage_form' => 'таблетки',
            'dosage' => '50 мг',
            'pack_size' => '10 таблеток',
            'manufacturer' => 'Ядран',
            'country' => 'Хорватия',
            'description' => 'Противорвотное и противоукачивающее средство',
            'atc_code' => 'R06AX05',
            'image_url' => 'https://example.com/dramina.jpg'
        ],
        [
            'trade_name' => 'Мотилиум',
            'inn' => 'Домперидон',
            'dosage_form' => 'таблетки',
            'dosage' => '10 мг',
            'pack_size' => '30 таблеток',
            'manufacturer' => 'Янссен',
            'country' => 'Бельгия',
            'description' => 'Противорвотное средство, стимулятор моторики ЖКТ',
            'atc_code' => 'A03FA03',
            'image_url' => 'https://example.com/motilium.jpg'
        ],
        [
            'trade_name' => 'Церукал',
            'inn' => 'Метоклопрамид',
            'dosage_form' => 'таблетки',
            'dosage' => '10 мг',
            'pack_size' => '50 таблеток',
            'manufacturer' => 'Плива',
            'country' => 'Хорватия',
            'description' => 'Противорвотное средство',
            'atc_code' => 'A03FA01',
            'image_url' => 'https://example.com/cerucal.jpg'
        ],
        [
            'trade_name' => 'Виагра',
            'inn' => 'Силденафил',
            'dosage_form' => 'таблетки',
            'dosage' => '50 мг',
            'pack_size' => '1 таблетка',
            'manufacturer' => 'Пфайзер',
            'country' => 'США',
            'description' => 'Препарат для лечения эректильной дисфункции',
            'atc_code' => 'G04BE03',
            'image_url' => 'https://example.com/viagra.jpg'
        ],
        [
            'trade_name' => 'Йодомарин',
            'inn' => 'Калия йодид',
            'dosage_form' => 'таблетки',
            'dosage' => '100 мкг',
            'pack_size' => '100 таблеток',
            'manufacturer' => 'Берлин-Хеми',
            'country' => 'Германия',
            'description' => 'Препарат йода для профилактики йододефицита',
            'atc_code' => 'H03CA',
            'image_url' => 'https://example.com/jodomarin.jpg'
        ],
        [
            'trade_name' => 'Аевит',
            'inn' => 'Ретинол + Токоферол',
            'dosage_form' => 'капсулы',
            'dosage' => '100000 МЕ+100 мг',
            'pack_size' => '20 капсул',
            'manufacturer' => 'Марбиофарм',
            'country' => 'Россия',
            'description' => 'Витаминный препарат',
            'atc_code' => 'A11CA',
            'image_url' => 'https://example.com/aevit.jpg'
        ],
        [
            'trade_name' => 'Аскорутин',
            'inn' => 'Аскорбиновая кислота + Рутин',
            'dosage_form' => 'таблетки',
            'dosage' => '50+50 мг',
            'pack_size' => '50 таблеток',
            'manufacturer' => 'Обновление',
            'country' => 'Россия',
            'description' => 'Витаминный препарат, укрепляющий капилляры',
            'atc_code' => 'C05CA51',
            'image_url' => 'https://example.com/askorutin.jpg'
        ]
    ];

    public function definition(): array
    {
        $medicine = $this->faker->randomElement($this->russianMedicines);

        return [
            'trade_name' => $medicine['trade_name'],
            'inn' => $medicine['inn'],
            'dosage_form' => $medicine['dosage_form'],
            'dosage' => $medicine['dosage'],
            'pack_size' => $medicine['pack_size'],
            'manufacturer' => $medicine['manufacturer'],
            'country' => $medicine['country'],
            'description' => $medicine['description'],
            'atc_code' => $medicine['atc_code'],
            'image_url' => $medicine['image_url'],
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => now(),
        ];
    }

    /**
     * Создать определенное количество лекарств
     */
    public function createMedicines(int $count = 50): array
    {
        $medicines = [];

        for ($i = 0; $i < min($count, count($this->russianMedicines)); $i++) {
            $medicine = $this->russianMedicines[$i];

            // Добавляем небольшие вариации в описании
            $medicine['description'] = $this->addVariationToDescription($medicine['description']);

            $medicines[] = $medicine;
        }

        return $medicines;
    }

    /**
     * Добавить вариации в описание для реалистичности
     */
    protected function addVariationToDescription(string $description): string
    {
        $variations = [
            "Применяется согласно инструкции и назначению врача.",
            "Хранить в сухом, защищенном от света месте при комнатной температуре.",
            "Противопоказания: индивидуальная непереносимость компонентов.",
            "Перед применением проконсультируйтесь с врачом.",
            "Не является лекарственным средством. БАД.",
            "Отпускается без рецепта врача.",
            "Срок годности: 2 года с даты производства."
        ];

        if ($this->faker->boolean(70)) {
            $description .= " " . $this->faker->randomElement($variations);
        }

        return $description;
    }

    /**
     * Указать конкретное лекарство по названию
     */
    public function withMedicine(string $tradeName): self
    {
        foreach ($this->russianMedicines as $medicine) {
            if ($medicine['trade_name'] === $tradeName) {
                return $this->state(function (array $attributes) use ($medicine) {
                    return [
                        'trade_name' => $medicine['trade_name'],
                        'inn' => $medicine['inn'],
                        'dosage_form' => $medicine['dosage_form'],
                        'dosage' => $medicine['dosage'],
                        'pack_size' => $medicine['pack_size'],
                        'manufacturer' => $medicine['manufacturer'],
                        'country' => $medicine['country'],
                        'description' => $medicine['description'],
                        'atc_code' => $medicine['atc_code'],
                        'image_url' => $medicine['image_url'],
                    ];
                });
            }
        }

        return $this;
    }
}
