import requests, re, sys
from bs4 import BeautifulSoup
notyet = True
def main():
    a = sys.argv[1]
    b = sys.argv[2]
    requestA = requests.get('http://tioj.infor.org/users/'+a.lower(), allow_redirects=False)
    if requestA.headers['Status'] != '200 OK':
        print("不要騙了，"+a+"根本不存在阿")
    else:
        if (b == 'nextone'):
            for i in range(1,31):
                bsme = BeautifulSoup( requests.get("http://tioj.infor.org/users?page=%d"%i).text, "html.parser").find("a", {"href": ("/users/"+a)})
                if bsme:
                    n = bsme.parent.parent.previous_sibling.previous_sibling
                    if not n:
                        if i-1 == 0:
                            b = 'NULL....'
                        else:
                            temp = BeautifulSoup( requests.get("http://tioj.infor.org/users?page="+str(i-1)).text, "html.parser").findAll("a", href=re.compile(r'/users/[^\?]'))
                            b = temp[len(temp)-1].text
                    else:
                        b = n.find("a")['href'].split("/")[2]
                    break
        if not b == 'NULL....':
            requestB = requests.get('http://tioj.infor.org/users/'+b.lower(), allow_redirects=False)
            if requestB.headers['Status'] != "200 OK":
                print("不要騙了，"+b+"根本不存在阿")
            else:
                if notyet:
                    soupA = BeautifulSoup(requestA.text, "html.parser")
                    soupB = BeautifulSoup(requestB.text, "html.parser")
                    listA = [i.text for i in soupA.find("table").tbody.findAll("a",{"class":"text-success"})]
                    listB = [i.text for i in soupB.find("table").tbody.findAll("a",{"class":"text-success"})]
                    same = [same for same in listA if same in listB]
                    onlyB = [oB for oB in listB if not oB in listA]
                    onlyA = [oA for oA in listA if not oA in listB]
                    html = '''<table class="pure-table">
                    <thead>
                      <tr>
                                    <th><a target="_newblank" href="http://tioj.infor.org/users/'''+b+'''">'''+b+'''</a>寫了以下你沒寫的題目呦：</th>
                                    <th>而你們都有寫這些題目：</th>
                                    <th>不過你也比<a target="_newblank" href="http://tioj.infor.org/users/'''+b+'''">'''+b+'''</a>多寫了這些題目阿：</th>
                            </tr>
                    </thead>
                    <tbody>'''
                    if len(same) >= len(onlyB) and len(same) >= len(onlyA):
                        for i in range(0, len(same)):
                            one = ''
                            two = ''
                            three = ''
                            if i <= len(onlyB)-1:
                                one = onlyB[i]
                            if i<=len(onlyA)-1:
                                three = onlyA[i]
                            two = same[i]
                            html+='''<tr>
                                    <td><a target="_newblank" href="http://tioj.infor.org/problems/'''+one+'''">'''+one+'''</a></td>
                                    <td><a target="_newblank" href="http://tioj.infor.org/problems/'''+two+'''">'''+two+'''</a></td>
                                    <td><a target="_newblank" href="http://tioj.infor.org/problems/'''+three+'''">'''+three+'''</a></td>
                            </tr>'''
                    elif len(onlyB) >= len(same) and len(onlyB) >= len(onlyA):
                        for i in range(0, len(onlyB)):
                            one = ''
                            two = ''
                            three = ''
                            if i <= len(same)-1:
                                two = same[i]
                            if i<=len(onlyA)-1:
                                three = onlyA[i]
                            one = onlyB[i]
                            html+='''<tr>
                                    <td><a target="_newblank" href="http://tioj.infor.org/problems/'''+one+'''">'''+one+'''</a></td>
                                    <td><a target="_newblank" href="http://tioj.infor.org/problems/'''+two+'''">'''+two+'''</a></td>
                                    <td><a target="_newblank" href="http://tioj.infor.org/problems/'''+three+'''">'''+three+'''</a></td>
                            </tr>'''
                    else:
                        for i in range(0, len(onlyA)):
                            one = ''
                            two = ''
                            three = ''
                            if i <= len(same)-1:
                                two = same[i]
                            if i<=len(onlyB)-1:
                                one = onlyB[i]
                            three = onlyA[i]
                            html+='''<tr>
                                    <td><a target="_newblank" href="http://tioj.infor.org/problems/'''+one+'''">'''+one+'''</a></td>
                                    <td><a target="_newblank" href="http://tioj.infor.org/problems/'''+two+'''">'''+two+'''</a></td>
                                    <td><a target="_newblank" href="http://tioj.infor.org/problems/'''+three+'''">'''+three+'''</a></td>
                            </tr>'''
                    html+='''</tbody>
            </table>'''
                    print(html.encode(encoding="utf8",errors='ignore').decode("utf8"))
        else:
            print('你已是TIOJ裡最強的了!!!'.encode("utf8").decode("utf8"))
if len(sys.argv) == 3:
    main()
