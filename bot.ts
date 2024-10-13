import { Telegraf, Context, Markup } from 'telegraf';

// Replace 'bot_token' with your actual bot token
const bot = new Telegraf('bot_token');
const adminId = 763563100;

// Keyboard definitions
const keys = Markup.keyboard([
    ['Men haqimda ✍🏻', 'Manzil 🔍'],
    [Markup.contactRequestButton('Tel raqam 📞'), 'Xabar jo`natish 📤']
]).resize();

const cancel = Markup.keyboard([['Ortga ⬅️']]).resize();

bot.start((ctx: Context) => {
    ctx.reply(`Assalomu aleykum ${ctx.from.first_name}`, { reply_markup: keys });
});

bot.hears('Men haqimda ✍🏻', (ctx: Context) => {
    ctx.reply(`Mening ismim Baxtiyor hozrda TATUUF talabasiman 👨🏻‍🎓. 
Programming menga hobbi desa ham bo'ladi💻
[Instagram 🔺](https://instagram.com/baxt1yor_)
[Facebook 🔹](https://facebook.com/baxtiyor.eshametov)
Email 📧 baxtiyoreshametov@yandex.ru 
[Batafsil](https://bit.ly/3bfrvPI)`, 
    { parse_mode: 'Markdown', reply_markup: keys });
});

bot.hears('Ortga ⬅️', (ctx: Context) => {
    ctx.reply('Bosh sahifa 🏠', { reply_markup: keys });
});

bot.hears('Manzil 🔍', (ctx: Context) => {
    ctx.replyWithLocation(41.285271, 61.210172, { reply_markup: keys });
});

bot.hears('Xabar jo`natish 📤', (ctx: Context) => {
    ctx.reply('Xabarni kiriting:', {
        reply_markup: {
            force_reply: true,
            selective: true
        }
    });
});

bot.on('text', async (ctx: Context) => {
    const text = ctx.message.text;
    const fromChatId = ctx.from.id;

    if (ctx.message.reply_to_message && ctx.message.reply_to_message.text === 'Xabarni kiriting:' && fromChatId !== adminId) {
        ctx.reply(`_☝🏻 Sizga shuni aytib o'tishim kerakki siz telegram sozlamalaringizdan "Uzatilgan xabarlar" bolimdan hammaga qilib qoyishingiz kerak shundagina admin xabari sizga yetib keladi_\n\n*Sizning xabringiz:* ${text}`, {
            parse_mode: 'Markdown',
            reply_markup: cancel
        });

        await bot.telegram.forwardMessage(adminId, fromChatId, ctx.message.message_id);
    } else if (ctx.message.reply_to_message && fromChatId === adminId) {
        const replyForwardId = ctx.message.reply_to_message.forward_from?.id;
        if (replyForwardId) {
            ctx.reply(`_Admin xabari sizga keldi_ \n${text}`, {
                parse_mode: 'Markdown',
                reply_markup: keys
            });

            // Handle media
            if (ctx.message.photo) {
                const photoId = ctx.message.photo[ctx.message.photo.length - 1].file_id;
                await ctx.telegram.sendPhoto(replyForwardId, photoId, { parse_mode: 'Markdown', reply_markup: keys });
            }

            if (ctx.message.document) {
                const documentId = ctx.message.document.file_id;
                await ctx.telegram.sendDocument(replyForwardId, documentId, { parse_mode: 'Markdown', reply_markup: keys });
            }

            if (ctx.message.audio) {
                const audioId = ctx.message.audio.file_id;
                await ctx.telegram.sendAudio(replyForwardId, audioId, { parse_mode: 'Markdown', reply_markup: keys });
            }

            if (ctx.message.voice) {
                const voiceId = ctx.message.voice.file_id;
                await ctx.telegram.sendVoice(replyForwardId, voiceId, { parse_mode: 'Markdown', reply_markup: keys });
            }

            ctx.reply(`*Javobingiz yuborildi shu id ga* \n${replyForwardId}`, {
                parse_mode: 'Markdown'
            });
        }
    } else if (ctx.message.contact) {
        const phone = ctx.message.contact.phone_number;
        ctx.telegram.sendContact(adminId, phone, ctx.from.first_name);
    } else {
        ctx.reply("_Sizning so'rovingiz bo'yicha hech narsa topilmadi. \nIltimos quyidagi menulardan birini tanglang_", {
            parse_mode: 'Markdown',
            reply_markup: keys
        });
    }
});

// Start the bot
bot.launch().then(() => {
    console.log('Bot is running...');
}).catch((err) => {
    console.error('Failed to launch the bot:', err);
});
