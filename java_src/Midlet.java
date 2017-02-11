package game;

import javax.microedition.midlet.*;
import javax.microedition.lcdui.*;
import java.util.*;
import java.io.IOException;

/**
 * @author erotic-games.ru
 */
public class Midlet extends MIDlet implements CommandListener{
    public Display disp;
    public Vector player = new Vector();
    public static String[] question = {
"tell with what part of a body do you associate #?",
"with what animal do you associate #?",
"do you know how # behaves in a bed. Tell about it.",
"where is a clitoris?",
"do you like when your partner has got sperm over himself? ",
"what do you prefer to be on top or below?",
"what role do you play in bed active or passive?",
"name the most erogenic zone on your body?",
"what size of boobs do you prefer small or big?",
"describe the appearance of your daydream person?",
"describe the character of your daydream person?",
"what sex tastes should have your daydream person? It can be classic, anal or oral sex, unusual positions, maybe some sexual perversions.",
"can you got interested in a man who always smokes?",
"can you got interested in a man who drinks a lot?",
"can you got interested in a man who treat himself to drugs? ",
"have you ever had sex?",
"Aren`t you tired of ordinal sex? Do you want something unusual and extreme?",
"how often do you have sex?",
"at what age did you have sex for the first time?",
"at what age do you want to stop fucking?",
"till what age do plan to keep ability to have sex?",
"at what age a man get sexual  appeal to you?",
"how older than you was your sex partner?",
"how younger than you was your sex partner?",
"at what age was your the youngest sex partner? ",
"at what age was your the oldest sex partner? ",
"do you prefer to have sex with experienced partner? Or it doesn`t matter?",
"is dick size matters to you?",
"tight vagina is an advantage, isn`t it?",
"what size is your genital organ?",
"how often do you watch porno films?",
"in what unusual place do you want to have sex?",
"what unusual way of sex do dream to have?",
"what is the most exotic place for having sex, how do you think?",
"tell about the most exotic place where you had sex?",
"what is the most exotic way of having sex, how do you think?",
"tell about the most exotic place where you had sex?",
"is sperm tasty, how do you think?",
"do you like when your partner get it off on you?",
"do you like when your partner get it off into your mouth?",
"do you like when your partner get it off on your face?",
"do you like to have sex with a person who have it for the first time?",
"just at the moment do you want sex with a person who will have it for the first time?",
"have you ever had sex with a person who have it for the first time?",
"is sex with drunk person  better then with sober one?",
"have you ever had sex with a dead drunk person?",
"just at the moment do you want sex with a drunk person?",
"have you ever had oral sex? ",
"have you ever had anal sex? ",
"did you have oral sex? Who was the licker?",
"do you like sex with robots?",
"just at the moment do you want sex with a robot?",
"have you ever had sex with a robot or with other mechanisms, for example, with vacuum cleaner, drier or washing mashine? ",
"have you ever have fantasies about having sex with robots?",
"have you ever have fantasies about having sex with mechanisms wich do not intended for sex?",
"have you ever had sex with several persons during one day?",
"have you ever had dates?",
"have you ever had passion kisses?",
"during dances have your parnter ever snuggle you up?",
"do you like to tell indecent jokes?",
"have you ever had soul kisses?",
"do you often have erotic dreams?",
"have you ever seen naked people?",
"have you ever toched other`s genital organ through clothes?",
"have you ever had erotic massage?",
"do you like smell of other`s wet underwear?  ",
"do you like to caress tits?",
"have you ever heard other people having sex?",
"have you ever watched other people having sex?",
"have you ever told somebody about your erotic fantasies?",
"have you ever discussed  sex with other people?",
"is this the first time when you play undress games? ",
"have you ever take a bath with a person of an opposit sex?",
"have you ever have sex in '69' position?",
"what things not intented for sex make you hot? May be it is shoes, heels, dishes, trees or something else?",
"can stockings make you hot?",
"can tights make you hot?",
"can pants make you hot?",
"what kind of clothes can make you hot?",
"how many times can you get it off during one night?",
"do you like to change positions during sex?",
"do you like to cuss during sex?",
"how do you call male genital organ?",
"how do you call female genital organ?",
"how do you call breast?",
"how do you call ass?",
"have you ever had sex without condom?",
"have you ever had sex in a car?",
"have you ever incline somebody to infidelity?",
"were you ever unfaithful to your partner? ",
"were your partner ever unfaithful to you?",
"have you ever had sex with a married person?",
"have you ever sex out of doors?",
"have you ever had sex in a public place?",
"is the fact that somebody can see you make you hot?",
"how many sex partners did you have?",
"have you ever had sex with a man who you knew only one day? ",
"have you ever had sex in other`s presence?",
"have you ever paid for sex?",
"what do you think of sex for money?",
"what do you think of sex with sluts?",
"have you ever licked a women`s pussy?",
"have you ever had an abortion?",
"do you have children?",
"have you your own family already?",
"have ever a woman masturbated you?",
"have ever a man masturbated you?",
"have you ever had sex with a sleeping person?",
"do you like to spy?",
"is the fact that sombody can spy upon you make you hot?",
"do you want to have sex with the president?",
"can authority make you hot?",
"have you ever fancied to have sex with an authority?",
"have you ever fancied to have sex with your boss?",
"have you ever had sex with your boss?",
"what is more important to you sex or work?",
"when you are busy with an important work your sex partner inclines you to make love. Will you leave your work to have sex?",
"can you freely tolk about your sex tastes?",
"how do you think your partner should guess about your sex preferencies or you can tell him about it?",
"{1do you like masturbation?",
"{1what do you think of self-satisfaction?",
"{1just at the moment do you want to masturbate?",
"{1do you masturbate?",
"{1have you ever used vibrator?",
"{1do you like to have things inside you?",
"{1just at the moment do you want to put something inside you? ",
"{1just at the moment do you want to put vibrator inside you?",
"{1have you ever fancied to put different things inside you?",
"{1have you ever discussed masturbation with anybody?",
"{1have you ever masturbated in anybody`s presence?",
"{1have you ever masturbated your partner?",
"{1have you ever fancied to masturbate in anybody`s presence?",
"{1have anybody ever caught you during masturbation?",
"{1have you ever fancied to masturbate in public place?",
"{1have you ever wished to masturbate anybody?",
"{2do you like pornography?",
"{2do you like look at pictures with naked men?",
"{2do you like look at pictures with men having sex?",
"{2just at the moment do you want to watch porno film?",
"{2have you ever read porno literature?",
"{2at what age did you watch porno films for the first time?",
"{2are you interested in pornography?",
"{2have you ever fancied to be in a porno film?",
"{2have you ever fancied to be in a porno film?",
"{2have you ever fancied to make a porno film?",
"{2do you have pictures where you are naked?",
"{2do you have pictures with your naked friends or relatives?",
"{2have you ever filmed your sex?",
"{2have you ever took pictures of your sex?",
"{2have you ever discussed pornography  with anybody?",
"{2do you like to watch porno film and masturbate?",
"{3do you like unisexual sex?",
"{3just at the moment do you want unisexual sex?",
"{3have you ever had unisexual sex?",
"{3have you ever fancied about unisexual sex?",
"{3have you ever fancied about sex with transsexual?",
"{3have you ever fancied about unisexual sex?",
"{3have you ever fancied about sex with man and woman at the same time?",
"{3have you ever discussed unisexual sex?",
"{3what do you prefer to swallow sperm or to spit it out?",
"{3have you ever fancied to have unisexual sex?",
"{4do you like sex with violence?",
"{4just at the moment do you want sex with violence?",
"{4have you sex with violence and domination?",
"{4have you ever fancied of being raped and tortured?",
"{4have you ever fancied to rape and torture anybody?",
"{4do you want your partner to hurt you during sex?",
"{4do you like pain in sex?",
"{4have you ever tolked about sex with violence?",
"{4have you ever been tied up during sex?",
"{4have you ever tied somebody up during sex?",
"{4have you ever put needles into your body to get pleasure?",
"{4have you ever droped molten wax on your body to get pleasure?",
"{4is self-injury makes you hot?",
"{4have you ever fancied to hurt anybody during sex?",
"{4have you ever fancied to be hurt during sex?",
"{4were you raped?",
"{4have you raped anybody?",
"{5do you like sex with old men?",
"{5just at the moment do you want sex with an old man?",
"{5have you ever had sex with an old man?",
"{5have you ever fancied to have sex with an old man?",
"{5can an old woman make you hot?",
"{5can an old man make you hot?",
"{5have you ever discussed with anybody sex with old men?",
"{5have you ever fancied to have sex with an old woman?",
"{5have you ever fancied to have sex with an old man?",
"{6do you like group sex?",
"{6have you ever had group sex?",
"{6just at the moment do you want to have sex with several people at the same time?",
"{6just at the moment do you want group sex?",
"{6have you ever fancied to have group sex?",
"{6have you ever fancied to have sex with several people at the same time?",
"{6have you ever discussed group sex?",
"{6have you ever fancied to fuck several people at the same time?",
"{6have you ever fancied to have group sex?",
"{6have you ever fancied that several people fuck you at the same time?",
"{7have you ever tasted your genital liquids? How do you like it?",
"{7do you like to have sex during menstruation?",
"{7do you want to have sex during menstruation?",
"{7have you ever had sex during menstruation?",
"{7have you ever fancied to have sex during menstruation?",
"{7have you ever licked woman`s pussy during her menstruation?",
"{7have you ever discussed sex during menstruation?",
"{7do you like sex during pregnancy?",
"{7have you ever discussed with anybody sex during pregnancy?",
"{7have you ever had sex during pregnancy?",
"{7have you ever fancied to have sex during pregnancy?",
"{7have you ever fancied to sex with  pregnant woman?",
"{7have you ever put your finger into your anus?",
"{7have you ever put your finger into other`s anus?",
"{7did anybody lick your anus?",
"{7have your tongue ever touched anus?",
"{7have you ever fancied to lick anus?",
"{7have you ever fancied that sombody lick your anus?",
"is it possible to make you hot at any time or there are times when are not able to get hot? ",
"can it be that making love bothers you?",
"do you always get pleasure in sex?",
"do you often make love? ",
"do you like to strip before your partner? ",
"do you like to make love in light?",
"are you able to try any sexual technique?",
"do you know all about sex?",
"do you want to know more about different sexual techniques?",
"{1can you masturbate before your partner?",
"do you often amuse yourself with an oral sex? ",
"sex must be had only in a bedroom, don`t you think so? ",
"do you tell your partner your secret erotic thoughts?  ",
"{1have you ever used vibrator or other sex toys?",
"do you tell your sex partner what thing stimulates you the most?",
"do you often try to have more than one fucking during one night?",
"is it difficult to concentrate during sex?",
"do you like when during day you happen to have sexual touches with different people?",
"{2do you think that sex magazines and porno films are disgusting?",
"do you take an active part in sex?",
"don`t you think that sex is overestimated?",
"do you wear sexy clothes to make your partner hot?",
"don`t you think that some sex techniques are disgusting?",
"if a man is physically well-developed  he can make love better, don`t you think?",
"do you consider yourself  a great expert on sex?",
"do you always try to find out your partner sexual neds?",
"do you think that you fully satisfied  all your sex partners?",
"do you always get your sex partners off?",
"do you like when your sex partner touches your anus?",
"do you like to kiss and caress your partner even if you are not having sex?",
"do you think that if you get old you will care for sex less? ",
"is your partner`s nudity excites you?",
"does it happen that you find your partner`s sex needs excessive?",
"do you want to advance your sexual potential?",
"{2will you let your partner to film you having sex?",
"do you want to feel more striking orgasm than you had before?"
    };
    public static String[] action = {
"@*touch … with your tongue.",
"@*poke your nose into ….",
"@*poke your heel into ….",
"@*poke your knee into ….",
"@*poke your elbow into ….",
"@*touch … with your tongue.",
"@*rub your breast against … .",
"imitate sex. Show how you move during sex.",
"say an obscence word.",
"*have a look under the skirt /into pants (if you  havn`t them already you can look at the sight). The object under observation is #.",
"*take something into your mouth (wallet, napkin, phone) and pass it to the other`s player mouth.  The other is #.",
"*write with your tongue the name #. Do it on the #`s  body.",
"*take off any clothes. It will be #`s clothes.",
"*let`s play at search. Let # hide something on the body (a paper, for example). You should find it.",
"show everyone with your arm the length of your penis or the derth of your vagina.",
" simulate masturbation and orgasm. ",
"tell a sexual secret. No lies!",
"show some erogenic zone on your body. ",
"*where do you want to be touched? # will fulfil your wish.  ",
"*where do you want to be kissed? # will fulfil your wish.  ",
"*where do you want to be caressed? # will fulfil your wish.  ",
"*do you want to hug anybody? # will fulfil your wish.  ",
"*do the 'butterfly kiss'. Move your eyelashes slowly on your partner`s cheek. It will be the  #`s cheek.",
"*practice 'Shy embrace' from Kamasutra. A woman hides her face on man`s breast, caresses his brest and neck and kisses his teat. You will do it with #.",
"*practice 'Drilling' from Kamasutra. A woman presses her body to a man, touches him with her breast and rubs her belly against man`s one. You will do it with #.",
"*practice 'Attractive embrace' from Kamasutra. A woman touches a man with her hips, presses herself to him letting his knees go between hers. You partner will be #.",
"*practice 'Urupa' from Kamasutra. A woman sits sidelong on man`s knees and makes exciting moves along them caressing his breast and neck with hands and lips.You partner will be #.",
"*practice kiss 'Tenderness' from Kamasutra. Touch slightly her/his lip with your tongue, enjoy the light touching. You partner will be #.",
"*practice kiss 'Mill' from Kamasutra. While kissing press slightly other`s tongue to you cheek with the help of your tongue and teeth. You will do it with #.",
"*practice kiss 'Shell' from Kamasutra. Kiss your partner`s earlap biting it a little. You partner will be #.",
"*practice kiss 'Sari' from Kamasutra. Kiss inner side of man`s palm and elbow. With this kiss a woman confesses that she wants to make love with this man. You will do it with #.",
"*practice kiss 'Elegant' from Kamasutra. Your tongue touches slightly your partner`s palate. You partner will be #.",
"*practice kiss 'Petal' from Kamasutra. A man slightly pulls aside a woman`s lower lip and put his tongue in her cheek exciting her with a light moves. You will do it with #.",
"*practice kiss 'Aga' from Kamasutra. Take your partner`s lip pull out his tongue and pull your tongue into his mouth. After it kiss your partner`s nose tip and his chin. You partner will be #.",
"*lick your partner`s earlap. You partner will be #.",
"*practice 'spoken kiss'. Touch your partner`s lips with yours and tell some tender things or some romantic poem. You will tell it to #.",
"*let`s make tongues war. Come closer to your partner, pull out your tongues and fight with them like with swords. The first who get tired will lose. You partner will be #.",
"*pinch your partner`s lips with yours. Take your partner`s lip into yours and pull it to yourself. Do it several times. You will do it with #.",
"*let`s make marathon kiss. Kiss with your partner as long as you can. The first who stop will lose. You will make it with  #.",
"*a lot of people adore their foot to be massaged. At first massage your partner`s heels then go to his toes. They are very sensitive, especially the little toe. You partner will be #.",
"*do you know where is the 'path of felicity'? It is the 'path' which starts from a belly-button and goes lower. Move tenderly your finger along your partner`s path making circular motions. You partner will be #.",
"*slap tenderly your partner`s ass. You partner will be #.  ",
"offer you ass. # will slap it tenderly.",
"which is your the most erogenic zone? Show it to everyone."
    };
    public static String[] softAction = {
"show an animal. Let # guess what it is.",
"miaul.",
"crow.",
"grow threateningly.",
"bleat like a sheep.",
"name some animal. # will be this animal.",
"Tenderly pronounce the name of #.",
"call somebody with a rude word",
"do you like name #? Rhyme it in a short poem.",
"*take something in your mouth (a pen, paper, fork) and put it on the other player`s palm. The other player will be  #.",
"*write with your finger your name on the other player`s body. The other player will be  #.",
"*let`s play 'motor-boat'. Bury your face in  #`s boobs and start up an engine 'br-br-br'",
"*let`s have a  lesson of anatomy. Think of some part of a body (heart, arm, kidney, heel). Take some pointer (or just use finger) and in a didactic teaching way show everyone where is this part of a body. You will show it on #`s body.",
"*let`s play at doctor. Let # show the part of a body that have a pain and you will heal it. ",
"croak like a frog. ",
"close your eyes, spread your hands aside and with each your forefinger touch your nose. Did you manage to do it?",
"stand in a swallow position: spread your hands aside, raise one leg backwards and bend your body forward.",
"show everyone a doggy style position.",
"*ask any question. # will answer it.",
"*come to your partner take his/her palm and  bending your body kiss it gallantly. Your partner will be #.",
"*let`s practice a kiss 'Choc-ice'. Touch slowly your partner`s nose with yours and move it to the left and to the right. Your partner will be #.",
"*let`s practice a kiss 'Stingray'. You and your partner stand in different corners then start to come closer to each other till there will be only 2 steps between you. Than you bend and touch each other only with your lips. The kiss shold be long untill you see stars. Mind not to fall.  Your partner will be #.",
"@*massage ….",
"*stand on your partner`s back and massage his arm.  Your partner will be #.",
"*caress your partner`s head. Your partner will be #."    
    };
    public static String[] hardAction = {
"give a soul kissing. You will do it with #.",
"@*rub your genitals against ….",
"*masturbate a little. You will do it to #.",
"masturbate yourself. Let everyone see it.",
"put something into your mouth and hold it till the end of the game or till you need your mouth for another task.",
"put something (finger, pen) into a vagina and hold it till the end of the game. You can chose any vagina.",
"put something into your ass and hold it till the end of the game or till you need it for another task.",
"show everyone your ass.",
"show everyone your breast.",
"show everyone your genitals.",
"*practice position 'Chivenaha' from Kamasutra. Woman lies on her back, her legs are raised, knees are bended and pressed to man`s sides. He tries to press her knees to her arms. This position widens vagina and increases depth of penetration. Your partner will be #.",
"*get your genitals closer to your partner`s mouth. # will lick it.",
"*practice position 'Clew' from Kamasutra. Man sits and outstretches his legs. Woman lies on his legs with her back and presses her knees to her breast. He enters her. This position puts off orgasm as it doesn`t allow penis to penetrate as deep as it can. At the same time it ia a powerful turn-on for both partners. Your partner will be #.",
"*practice position 'Lotus flower' from Kamasutra. Woman lies on her back, raises her legs and foldes them one over the other. This position miniaturizes vagina and increases intensity of feelings. Your partner will be #.",
"put your finger into your vagina.",
"put your finger into your ass.",
"put your finger into your mouth.",
"*practice position 69: your partner lies on you with his genitals turned to your mouth, you should lick each other`s genitals. You will do it with #.",
"*let`s make love in doggy-style position. Woman props herself on her knees and hands and turns her ass to a man. The man enters her from behind. He can use any hole. Your partner will be #.",
"*practice missionary position. Woman lies on her back, man enters her from above. Your partner will be #.",
"*let`s have oral sex. # will lick your organ.",
"*let`s have oral sex. You will lick #`s organ.",
"*put your penis between your partner`s boobs and move it back and forth. Your partner will be #.",
"*move balanus on vulvar lips. Your partner will be #.",
"*draw your partner`s lips into your mouth and caress them with your tongue. Your partner will be #.",
"*practice position 'Cylon' from Kamasutra. Woman lies on her back, outstretches and parts widely her legs. Man lies between her legs and enters her. You will do it with #.",
"*practice position 'Couch' from Kamasutra. Woman lies on her back with hre knees bended a little. Man lies between her legs and enters her. You will do it with #.",
"*practice position 'Husband`s delight' from Kamasutra. Woman lies on her back and puts her ass on a pillow. Man enters her from above. You will do it with #.",
"*practice position 'Podjaraki' from Kamasutra. Woman lies on her back, man is on top. He pulls her hips to himself and parts them as wide as possible. She takes her feet in her hands and pulls them up and to herself. You will do it with #.",
"*practice position 'Indiane' from Kamasutra. Woman sits on her knees, parts her hips and leans back. This position is uncomfortable but pleasurable. Your partner will be #.",
"*practice position 'Scorpion' from Kamasutra. Woman lies on her back, man is on top. He enters her but doesn`t move. The both partners outstretch their legs and hands and relax. Your partner will be #.",
"*practice position 'Lotus stalk' from Kamasutra. Woman lies on her back and puts penis into herself. Then she puts her legs one upon the other in front of man`s belly. You will do it with #.",
"*practice position 'Basket' from Kamasutra. Man sits with outstretched legs. Woman lies her back on his legs, presses her knees to her breast and sets her feet against man`s belly. This position puts off orgasm as it doesn`t allow penis to penetrate as deep as it can. At the same time it is a powerful turn-on for both partners. Your partner will be #.",
"*practice position 'Peacock' from Kamasutra. Woman presses one of her legs to her breast and outstretches the other. This position tightens the hole but permits to put penis very deeply. It increases woman`s pleasure. You will do it with #.",
"*practice 'Soft position' from Kamasutra. Woman lies on her back. Her legs are widely parted and raised as high as possible. Your partner will be #.",
"*practice position 'Two Columns' from Kamasutra. Woman lies on her back with legs widely put apart and raised straight up. Only man moves, the woman just swings her body a little. Your partner will be #.",
"*practice position 'Square' from Kamasutra. Woman lies on her back with legs widely put apart and raised straight up. Man puts his hands on inner side of her hips and pulls them apart. Then he enters her and makes her hips to move as he wants. Your partner will be #.",
"*practice position 'Babylon' from Kamasutra. Woman lies on her back with legs widely put apart and raised straight up. Man holds her heels. You will do it with #.",
"*practice position 'Throne' from Kamasutra. Man sits, outstretches his legs and puts his hands on his knees. Woman sits on his hands with her back turned to him. Your partner will be #.",
"*practice position 'Maternity' from Kamasutra. Woman sits sidelong on man`s knees, puts his penis into herself and presses her legs together. She puts her face on man`s breast and kisses his teats.Your partner will be #.",
"*practice position 'Saddle' from Kamasutra. Woman sits sidelong on man`s knees and puts his penis into herself. She puts one her leg on his arm or his neck (if she can).Your partner will be #.",
"*practice position 'Boat' from Kamasutra. Woman squats on a bench with her back turned to man. He sits on the bench and enters her from below. Your partner will be #.",
"*practice position 'Simple Sheta' from Kamasutra. It`s a standing position. Man and woman stand face to face. The woman puts her legs apart and the man enters her. She tightens her hips and starts to move, he helps her. You will do it with #.",
"*practice position 'Heron' from Kamasutra. It`s a standing position. Man and woman stand face to face. Woman puts one her leg on a rising ground or holds it hanging. Man enters her. You will do it with #.",
"*practice position 'Coco' from Kamasutra. Man stands astraddle and sets his back against a wall. Woman hangs on his neck, rests her knees or feet on his palms and puts his penis into herself. Your partner will be #.",
"*practice position 'Harasheta' from Kamasutra. Man stands astraddle and sets his back against a wall. Woman hangs on his neck. The man holds her hips. Your partner will be #.",
"*practice position 'Unupriata' from Kamasutra. Man stands astraddle and sets his back against a wall. Woman puts her knees on his elbows. Your partner will be #.",
"*practice position 'Two Spears' from Kamasutra. Woman lies on her back with outstretched legs. She lets man enter her and presses her legs together. Your partner will be #.",
"*practice position 'Chastity' from Kamasutra. Woman lies on her back with outstretched and pressed together legs. A men presses his legs against her hips and enters her.Your partner will be #.",
"*practice missionary position. Woman lies on her back with outstretched and slightly parted legs. Man enters her. You will do it with #.",
"*practice position 'Niaduk' from Kamasutra. Woman bends and puts her hands on a floor. Her legs are straight. Man enters her, leans to her and slowly starts to move. You will do it with #.",
"*practice position 'Horse-woman'. Man lies on his back. Woman sits on his penis and starts to move. In this position the man can caress woman`s hips, breasts or clitoris. You will do it with #.",
"*practice position 'Woman-on-top'. Man lies on his back. Woman lies on him and enters his penis into herself. The woman takes an active part in this position. Your partner will be #.",
"*practice 'Jump to Happiness' from Kamasutra. Woman embraces man`s neck, draws in her legs and tries to press her belly to his genitals. Such embrace increases sexual tension and makes man go out of his mind. Your partner will be #.",
"*practice embrace 'Outside' from Kamasutra. Man stands behind woman pressing her to himself. He caresses her breast and belly. She holds one of her hands on his neck and with another hand she touches his belly and penis. You will do it with #.",
"*practice embrace 'Liana interlacing' from Kamasutra. Woman hangs on man`s neck, interlaces his knees with her feet and presses her belly to his penis. Such embrace increases sexual tension and makes a man go out of his mind. Your partner will be #.",
"*practice caressing 'Lotus' from Kamasutra. Woman sits on man`s knees with her back pressed to his breast. He caresses her breast , belly, hips and knees, kisses her neck and lips. She let his penis goes between her hips but not into her vagina and start to make exciting moves. Your partner will be #.",
"*practice caressing 'Horse-woman' from Kamasutra. Man lies on his back. Woman sits on him touching his penis with her underbelly. She caresses him with her breast and hips. Your partner will be #.",
"*practice position 'Jungle' from Kamasutra. This position is without penetration. Woman lies on her back, her legs embrace man`s sides. The man puts his head on her breast, his legs on her hips and starts to caress her breast and belly.Your partner will be #.",
"*practice kiss 'Samayan' from Kamasutra. Man takes woman`s tongue into his mouth and starts to suck it. She tries to swallow his tongue. You will do it with #.",
"*practice technique 'Grasp' from Kamasutra. You take any position. During fucking woman toughens muscles of her vagina delaying but not stopping his movements. Your partner will be #.",
"*practice position 'Boat swaying' from Kamasutra. You should lie at a wall in man-on-top position. Woman outstretches her legs and set them against the wall. You will do it with #.",
"*practice technique 'Rotation' from Kamasutra. You take any position. Woman turns her legs outside then presses them against man`s sides turning her hips and knees inside. Your partner will be #.",
"*practice technique 'Swan' from Kamasutra. Woman lies on her back, man is on top. She rests one her leg against a bed, another one puts high in the air and slightly strikes the man with her pubis.You will do it with #.",
"*practice technique 'Throw' from Kamasutra. You take any position. Woman toughens muscles of her belly and raises it when man enters her. Your partner will be #.",
"*practice technique 'Snake' from Kamasutra. You take any position. Man enters woman and she tries to touch the man`s body with her breast, sides, belly and hips. She can help herself with her hands. You will do it with #.",
"*practice technique 'Belly' from Kamasutra. Take man-on-top position. Woman starts to pull up and down her belly holding man on it. She helps herself only with her legs. You will do it with #.",
"*practice technique 'Bridge' from Kamasutra. Woman lies, raises her belly and puts something under her hips. Her legs are parted but not raised. Man enters her touching only her pubis. Your partner will be #.",
"*practice technique 'Wild Boar thrust' from Kamasutra. You take man-on-top position. Man bend his belly down and makes thrusts under her pubis. Your partner will be #.",
"*practice technique 'Dart' from Kamasutra. Take man-on-top position. Man moves his penis top-down. At first he should press it to upper wall of vagina and then suddenly move it down. Woman takes semisitting position, presses herself to the man, holds his hips and with a slight move guides his pennis to the lower wall of her vagina. You will do it with #.",
"*practice technique 'Across a flow' from Kamasutra. Take any position. Man enters woman. His balanus touches upper wall of vagina than the lower one by turns . You will do it with #.",
"*practice technique 'Spear' from Kamasutra. Take any position. Man suddenly stops fucking and doesn`t move for some time. This technique increases woman`s sexual tension. Your partner will be #.",
"*practice technique 'Swing' from Kamasutra. Take any position. Man moves evenly and fluently inside woman. Your partner will be #.",
"*practice technique 'Steps' from Kamasutra. Take any position where man has freedom of action. Man makes three shallow thrusts at first and then suddenly enters woman as deep as he can. Your partner will be #.",
"*practice technique 'Ram' from Kamasutra. Take any position. Man enters deeply woman then makes a deeper furious movement trying to touch her uternin. You will do it with #.",
"*practice 'Loving technique' from Kamasutra. Take any position. Man deeply enters woman and than makes some movements deeper then slowly moves out. After some pause he enters her again. You will do it with #.",
"*practice technique 'Man`s handshake' from Kamasutra. Take any position. Man enters woman over her leg. He moves his penis only at one side of her vagina pressing it as tight as he can. Your partner will be #.",
"*practice technique 'Rooster' from Kamasutra. Take any position. Man several times touches with his penis the entry of vagina then suddenly enters it and makes some circular movements inside it. The woman helps him rotating her hips. You will do it with #.",
"*practice technique 'Sparrow' from Kamasutra. Man enters woman as deep as he can, makes three tender movements and gets out.You will do it with #.",
"*practice technique 'Thrust' from Kamasutra. Take any position. Man puts his penis into woman with the movement of his hips and underbelly. Your partner will be #.",
"*practice technique 'Chain' from Kamasutra. Take man-on-top position. Man starts to go up and down proping himself on his arms. You will do it with #.",
"*practice technique 'Dolphin' from Kamasutra. Partners move touching each other with the whole body, breast to breast, belly to belly, legs to legs. Your partner will be #.",
"*practice technique 'Ring of love' from Kamasutra. Man simultaneously with every move of his penis raises one of his sides and then another. You will do it with #.",
"*let`s make love. While entering man rotates his penis with his hand pressing it to different sides of vagina. Your partner will be #.",
"*practice 'Village position' from Kamasutra. A man sits crossing his legs.Woman sits on his hips, enters his penis into herself, embraces his arms and starts to move her underbelly.You will do it with #.",
"*practice position 'Horseman' from Kamasutra. Woman sits with her hips parted a little. A man sits on her hips face to face, enters her and starts to move.You will do it with #.",
"*practice position 'Indrana' from Kamasutra. A man sits with outstretched legs. Woman sits on his legs pressing her hips to his groin, enters his penis into herself and starts to move back and forth. Your partner will be #.",
"*practice position 'Double Parchala' from Kamasutra. Woman lies on her back. A man lies on her with his face to her legs, enters her and starts to move. You will do it with #.",
"*practice position 'Turtle' from Kamasutra. Woman lies on her back with her legs high in the air bended in knees and pressed to his sides. A man tries to press her knees to her arms and makes deep sudden moves.Your partner will be #.",
"*practice position 'Second wife' from Kamasutra. Woman lies on her back, her legs are outstretched and parted. A man enters her. You will do it with #.",
"*practice position 'Indiane suta' from Kamasutra. Woman sits on her knees, presses her hips together and bends on her back. Man enters her from above. The woman feels uncomfortable but gets intense pleasure. Your partner will be #.",
"*let`s stimulate a scrotum. Man takes any position. Woman takes man`s balls in her hand, caress and roll them. You will do it with #.",
"*how about stimulating an anus? Caress an anus with your tongue. You washed the anus, didn`t you? You will do it to #.",
"*lick feet. You will do it to #.",
"*lick heels. You will do it to #.",
"*put toes in to your mouth and suck them. You will do it to #.",
"*let`s have anal sex? If you never had it before you should do the follow: at first give an enema, properly wash the anus; start with one finger when the anus get used to it add the second and the third one. After it man enters the anus. He should move slowly and not deeply. Your partner will be #.",
"*put your finger into anus. You will do it to #. Men like when woman presses her finger to pubis as there is a prostate there.",
"*practice oral sex with anal penetration. Woman takes penis into her mouth and starts to suck it, at the same time she puts her finger into man`s anus and slowly pulls it to herself. When man starts to get it off woman will feel that his prostate strains. After orgasm you should get your finger out tenderly as anus will be very sensitive. You will do it with #.",
"*practice oral sex with scrotum stimulation. Woman takes penis into her mouth and balls into her hand. She starts to suck penis and to caress his scrotum. Your partner will be #.",
"*practice oral sex. Woman lies on her back, man puts his face between her hips. He starts to lick her large vulvar lips then moves to small ones. After it he should stimulate clitoris. Your partner will be #.",
"*practice anal sex in woman-on-top position. Man lies on his back, woman sits on him and enters his penis into her anus. Woman controls depth of penetration and intensity of movements. You will do it with #.",
"*practice anal sex in missionary position. Take man-on-top position. He slowly puts his oiled penis into her anus.Your partner will be #.",
"*let`s practice anal sex in doggy-style position. Woman stands on her knees and props herself on her arms. A men tenderly and slowly enters her anus from behind. You will do it with #.",
"*practice oral sex. Woman lies on her back, man puts his face between her legs and starts to kiss her belly gradually going down to her genitals. When the man reaches clitoris he puts his fingers into her vagina. Your partner will be #.",
"*practice massage of vulva. Man puts his palms together in a way to get a kind of a scoop. He puts such scoop on vagina and hold it for some time. Then he tendely presses large vulva lip with his thumb and forefinger and slowly slips his hand to pubis and then from side to side. In similar way you can caress small vulva lips but with much care in order not to hurt woman.You will do it with #.",
"*practice massage of clitoris. Man tenderly clenches skin over and above clitoris and starts to pull it up untill fingers come off. Do it several times. Your partner will be #.",
"*practice technique 'Clock with a secret'. Man draws circles around clitoris with his forefinger. He should stop every time when reaches position 'noon'. You will do it with #.",
"*practice technique 'Door bell'. Man tenderly presses clitoris with his thumb-cushion as if he rings a doorbell. He should do it several times. But he should remember to take away his thumb from the 'bell' after pressing. Such technique can easily get woman to orgasm.Your partner will be #.",
"*practice massage of vagina. Man slowly puts his finger to vagina, lets woman 'suck' it inside. At first he holds it without movements then tenderly presses each side of vagina several times.You will do it with #.",
"*practice technique 'Half moon'. Man enters his thumb into vagina in a way that his palm closes clitoris and his fingers repose on pubis. Then he tenderly moves his finger in different directions.Your partner will be #.",
"*find zone 'G'. Man enters his forefinger in a way that inner side of his palm was outside. Then he moves his finger along the wall of vagina that is closer to belly. While examining vagina the man askes the woman where is the place that she feels current rush. When the man finds 'zone G' (it can be of a coin size) he can caress it a little longer. This can give more sexual tension than clitoris stimulating. You will do it with #.",
"*practice technique 'Corkscrew'. Man tenderly enters vagina with his forfinger and middle finger and turns his hand at the same time. Do it several times. Your partner will be #.",
"*practice massage of penis. Woman takes penis into her hand and starts to massage along it with tender movements. It is better to have some grease. Your partner will be #.",
"*practice massage of frenulum. It is a part of a penis connecting prepuce and balanus. It is the most sensitive zone. Woman tenderly masseges it. Your partner will be #.",
"*let`s make a prostate massage. Woman greases her finger and man`s anus. She slowly and tenderly puts her finger into his anus. Than she presses her finger to his pubis making waving moves. Do you feel something of a walnut size? It is a prostate. You should massage it. It will drive your partner mad. You will do it with #."    
    };
    public Image ActionSoft, Action, ActionHard, Question, Touch, Сaress, Kiss, Pants, Strip;
    public Image hdr, bgrnd;//ftr, 
    public Settings sett;
    public int movN = 0; // количество ходов с начала игры
    //public Dialog dialog = new Dialog();
    //public Form fMess; // форма с сообщением о ходе следующего игрока
    //private StringItem mess; //
    private Alert nextmov;
    private Player p; // текущий игрок
    private int curPlayer;
    public Rnd rnd = new Rnd();
    public Midlet() {
      try{  
        ActionSoft = Image.createImage("/asoft.png");
        Action = Image.createImage("/action.png");
        ActionHard = Image.createImage("/ahard.png");
        Question = Image.createImage("/question.png");
        Touch = Image.createImage("/touch.png");
        Сaress = Image.createImage("/caress.png");
        Kiss = Image.createImage("/kiss.png");
        Pants = Image.createImage("/pants.png");
        Strip = Image.createImage("/strip.png");
        hdr = Image.createImage("/sp.png");
        //ftr = Image.createImage("/ftr.png");
        bgrnd = Image.createImage("/bgn.png");
      }catch(IOException ex){
            Alert a = new Alert("Error","Can't load images. "+ex.getMessage(), null, AlertType.ERROR);
            disp.setCurrent(a);
      }
        /*mess = new StringItem("", "", StringItem.PLAIN);
        mess.setLayout(StringItem.LAYOUT_CENTER|StringItem.LAYOUT_VCENTER|StringItem.LAYOUT_VEXPAND);
        fMess = new Form("СоблазнениЕ");
        fMess.append(mess);*/
        disp = Display.getDisplay(this);
        sett = new Settings(this);
    }
    public void startApp() {
        if(disp.getCurrent()==null)
            disp.setCurrent(sett);
    }
    public void pauseApp() {
    }
    public void destroyApp(boolean unconditional) {
    }
    public void exit() {
        destroyApp(false);
        notifyDestroyed();
    }
    public void play() {
        curPlayer = rnd(sett.pN);
        p = (Player)player.elementAt(curPlayer);
        nextPlayer();
    }
    public void nextPlayer() {
        movN++;
        if(p.scr >= 100 && !sett.isInf) { // Победа!!!
            gameOver();
            return;
        }
        if(++curPlayer == sett.pN) curPlayer = 0; //переходим к первому игроку
        p = (Player)player.elementAt(curPlayer);
        /*mess.setText(p.name+" ходит");
        disp.setCurrent(fMess);*/
        nextmov = new Alert(p.name+" moves");
        nextmov.setTimeout(1100);
        nextmov.setCommandListener(this);
        disp.setCurrent(nextmov);
        //p.ask();
    }
    private void gameOver(){
        String s;
        if(p.isMale) s = p.name+" wins! He won a right of fulfiling one of his wishes.";
        else s = p.name+" wins! Now she has a right of fulfiling one of his wishes.";
        Alert a = new Alert("Hurrah!!!", s, null, null);
        a.setTimeout(Alert.FOREVER);
        a.addCommand(new Command("Ok", Command.EXIT, 1));
        a.setCommandListener(this);
        disp.setCurrent(a);
        disp.vibrate(2000);
        for(int i=0; i < sett.pN; i++){
            p = (Player)player.elementAt(i);
            p.clear();
        }
        movN = 0;
    }
    public void commandAction(Command c, Displayable d){
        if (d == nextmov)// если вызвали из сообщения nextmov
            p.ask();    
        else // вызывали из gameOver()
            disp.setCurrent(sett);
    } 
    public int rnd(int n) { return rnd.get(n); } 
}
