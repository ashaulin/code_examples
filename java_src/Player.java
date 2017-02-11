package game;

import javax.microedition.midlet.*;
import javax.microedition.lcdui.*;
import java.util.*;

/**
 *
 * @author erotic-games.ru
 */
public class Player implements CommandListener{
    public boolean isMale;
    public String name;
    public int scr;
    public int scrinc; // величина, на которую изменяется очарование в зависимости от задания
    public Midlet ml;
    public Dialog dialog;
    public Alert a;
    //private Command done, none, oponent;
    private Vector usesQuestions = new Vector();
    private boolean isSelReg; // нужно выбирать часть тела
    private boolean isOponent; // в задании есть партнёр
    private int who; // номер партнёра в player
    private Integer strN; //номер текущего вопроса
    private boolean noPants = false; //трусы ещё на месте?
    // массив строк с частями тела для выбора
    private String[] regions = {"hand", "elbow", "underarm", "arm", "forearm", "side", "under knee",
                             "knee", "back", "shank", "foot", "hair", "neck", "ear",
                             "forehead", "nose", "eye", "cheek", "between breasts", "lips", "belly",
                             "thigh",  "breast", "bottom", "pubis", "genital organ"};
    public Player(String _name, boolean _isMen){
        name = _name;
        isMale = _isMen;
        scr = 0;
        a = new Alert("Result", "", null, null);
        a.setTimeout(5000);
        a.addCommand(new Command("Ok", Command.EXIT, 1));
        a.setCommandListener(this);
    }
    public void setMl(Midlet _ml){ ml = _ml; }
    public void ask()
    {
        isSelReg = false;
        isOponent = false;
        int i = ml.movN / 8 + 2; // каждые 8 шагов добавляем по одному допустимому заданию
        i = (i>9) ? 9:i;
        switch(ml.rnd(i)){ // случайно выбираем задание
            case 1: showTouch(); break;
            case 2: showSoftAction(); break;
            case 3: showCaress(); break;
            case 4: showAction(); break;
            case 5: showKiss(); break;
            case 6: showStrip(); break;
            case 8: showHardAction(); break;
            case 7: showPants(); break;
            default : showQuestion();
        } 
    }
    private void showDialog(String msg, String comDone, String comNone, Image bgSibl) {
        if (isSelReg){ // нужно выбирать часть тела?
            int region = scr / 4 + 5; // чем больше очарования, тем больше позволено;
            region = (region > regions.length) ? regions.length:region; //не выходим за пределы массива 
            region = ml.rnd(region);
            if (ml.sett.isSoft && region >= regions.length-3) region = 19; // не позволяем выбирать гениталии в мягкой версии игры
            msg = replStoS(msg, "…", regions[region]);// заменяем троеточие частью тела
            Player p;
            do{// случайно выбираем игрока
                who= ml.rnd(ml.sett.pN);
                p = (Player)ml.player.elementAt(who);
                if(p == this){ // уменьшаем вероятность игры с самим собой
                    who= ml.rnd(ml.sett.pN);
                    p = (Player)ml.player.elementAt(who);
                }
            // пока игроки не будут разных полов
            }while (isMale == p.isMale &&
                (p != this || (bgSibl == ml.Kiss)) ); // отключаем поцелуи себя в губы
            msg = replStoS(msg, "#", p.name);
            if (p == this){
                msg += " Do it to yourself.";
                isOponent = false;
            }else{
                if(ml.sett.pN != 2)
                    msg += " Your partner will be " + p.name + ".";
                else msg += " Do it to the person next to you.";
            }
        }
        dialog = new Dialog(isOponent, comDone, comNone, msg, this, bgSibl);
        ml.disp.setCurrent(dialog);
    }
    public void commandAction(Command c, Displayable dis) {
        //if (dis == a) {// если команда Ок из mess()
            ml.nextPlayer();
        //}
    }
    public void done(){
        scr+=scrinc;
        mess(name+", well done! Your charm increased to "+scrinc+".");
    }
    public void none(){
        scr-=scrinc;
        mess(name+" loses charm. Now you have only "+scr+".");
    }
    public void oponent(){ // партнёр отказался выполнять задания
        Player p; 
        p =  (Player)ml.player.elementAt(who);
        p.scr-=scrinc;
        mess(p.name+" loses charm. Now you have only "+p.scr+".");
    }
    private void mess(String msg){
        StringItem mess = new StringItem("", msg, StringItem.PLAIN);
        mess.setLayout(StringItem.LAYOUT_CENTER|StringItem.LAYOUT_VCENTER|StringItem.LAYOUT_VEXPAND);
        Form fMess = new Form("Result");
        fMess.append(mess);
        fMess.addCommand(new Command("Ok", Command.EXIT, 1));
        fMess.setCommandListener(this);
        //a.setString(msg);
        ml.disp.setCurrent(fMess);
        dialog = null;
    }
    private String selectString(String[] strings) {
	String s;
	int grp;
	do{
            strN = new Integer(ml.rnd(strings.length));
            s = new String(strings[strN.intValue()]);
            if (s.charAt(0) == '@'){ // если в начале строки стоит символ @
                s = s.substring(1); // удаляем его
                isSelReg = true; // нужно выводить силуэт
            }
            if (s.charAt(0) == '*'){ // если в начале строки стоит символ *
                s = s.substring(1); // удаляем его
                isOponent = true; // для действия существует опонент
            }
            if (s.charAt(0) == '{'){ // если в начале строки стоит группирующая последовательность
                grp = s.charAt(1) - 49; // вычленяем её
                s = s.substring(2); // удаляем её
            }else
                grp = 0;
	}while(!ml.sett.allowGrps[grp]); // проверяем на допустимость вопроса
	Player p;
        do{// случайно выбираем игрока
            who= ml.rnd(ml.sett.pN);
            p = (Player)ml.player.elementAt(who);
            //trace("пол игрока:"+players[curent_player].gender+" выпал игрок пола:"+players[who].gender);
	// пока игроки не будут разных полов
	}while (isMale == p.isMale);
        return name + ", " + replStoS(s, "#", p.name);
    }
    private void showQuestion(){//показываем диалог с вопросом
        String msg;
        boolean repeat;
        do{
            msg = selectString(ml.question);
            repeat = false;
            if (usesQuestions.size() >=  ml.question.length){
                    msg = "you have been asked all the questions.";
                    break;
            }
            for (int i = 0; i < usesQuestions.size(); i++)
                if (strN == usesQuestions.elementAt(i)){
                    repeat = true;
                    //trace("Вопрос №"+msg.strN+" уже задавался этому игроку - ищем другой");
                    break;
                }
        } while (repeat); // повторяем пока не найдём ещё незаданного вопроса
        usesQuestions.addElement(strN); // сохраняем уже заданный вопрос
        scrinc = 1;
        showDialog(msg, "Answered", "Won`t tell",  ml.Question);
    }
    private void showSoftAction(){//показываем диалог с простым действием
	scrinc = 2;
        showDialog(selectString(ml.softAction), "Done!", "No-o-o", ml.ActionSoft);
    }
    private void showAction(){//показываем диалог с усложнённым действием
	scrinc = 3;
        showDialog(selectString(ml.action), "Done", "I can`t", ml.Action);
    }
    private void showHardAction(){//показываем диалог с трудновыполнимым действием
	scrinc = 5;
        if(ml.sett.isSoft||scr<70) {showAction(); return;}
        showDialog(selectString(ml.hardAction), "Done", "I won`t!", ml.ActionHard);
    }
    private void showStrip(){//показываем диалог с предложением раздеться
	scrinc = 6;
        if (isMale)
            showDialog(name + ", take off one piece of clothing!", "Done", "I can`t", ml.Strip);
	else
            showDialog(name + ", take off anything.", "Done", "I can`t", ml.Strip);
    }
    private void showPants(){//показываем диалог с предложением снять трусы
        if(noPants){showStrip(); return;}// трусов уже нет
        noPants = true;
	scrinc = 9;
        if (isMale)
            showDialog(name + ", take off pants! You can leave the rest clothes. Think over how to do it.", "Taken off", "I can`t", ml.Pants);
	else
            showDialog(name + ", take off your pants! You can leave the rest clothes. You are fortunate if you wear a skirt.", "Taken off", "I can`t", ml.Pants);
    }
    private void showKiss(){//показываем диалог "Поцелуй"
        scrinc = 4;
        isSelReg = true;
        isOponent = true;
	showDialog(name + ", kiss ….", "Slurp", "I can`t", ml.Kiss);
    }
    private void showCaress(){//показываем диалог "Погладь"
        scrinc = 2;
        isSelReg = true;
        isOponent = true;
	showDialog(name + ", caress … with your hand.", "Aye-aye!", "No", ml.Сaress);
    }
    private void showTouch(){//показываем диалог "Прикаснись"
        scrinc = 1;
        isSelReg = true;
        isOponent = true;
	showDialog(name + ", touch ….", "Done", "I won`t", ml.Touch);
    }
    private String replStoS(String s, String ch, String rep){
        int idx = s.indexOf(ch);
        if(idx > -1){
            String grp = s.substring(idx+1); //копируем от # до конца строки
            s = s.substring(0, idx)+ rep + grp; // заменяем # именем соперника
        }
        return s;
    }
    public void clear(){
        scr = 0;
        noPants = false;
    }
}
