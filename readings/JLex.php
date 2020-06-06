<html>
<head>
<link rel="stylesheet" href="dextras.css" />

<script type="text/x-mathjax-config">
    // <![CDATA[
    MathJax.Hub.Config({
        TeX: {extensions: ["AMSmath.js", "AMSsymbols.js"]},
        extensions: ["tex2jax.js"],
        jax: ["input/TeX", "output/HTML-CSS"],
        showProcessingMessages : false,
        messageStyle : "none" ,
        showMathMenu: false ,
        tex2jax: {
            processEnvironments: true,
            inlineMath: [ ['$','$'] ],
            displayMath: [ ['$$','$$'] ],
            preview : "none",
            processEscapes: true
        },
        "HTML-CSS": { linebreaks: { automatic:true, width: "latex-container"} }
    });
    // ]]>
 // MathJax.Hub.Queue(reportReload);
</script>
<script type="text/javascript" src="MathJax.js"></script>
<script type="text/javascript" src="latex2html5.min.js"></script>


</head>
<body>
<HR>
<H2>Contents</H2>

<UL>
  <LI><A HREF="#overview">Overview</A>
  <LI><A HREF="#format">Format of a JLex Specification</A>
  <UL>
    <LI><A HREF="#rules">Regular Expression Rules</A>
    <UL>
      <LI><A HREF="#youtry1">Test Yourself #1</A>
    </UL>
    <LI><A HREF="#dirs">JLex Directives</A>
    <UL>
      <LI><A HREF="#youtry2">Test Yourself #2</A>
    </UL>
    <LI><A HREF="#comments">Comments</A>
    <LI><A HREF="#states">States</A>
    <UL>
      <LI><A HREF="#youtry3">Test Yourself #3</A>
    </UL>
  </UL>
  <LI><A HREF="#vars">yyline and yytext</A>
  <LI><A HREF="#example">A Small Example</A>
  <LI><A HREF="#quick">Quick Reference Guide</A>
</UL>

<P>
<a name=overview></a>
<H2>Overview</H2>
<P>
JLex is a scanner generator that produces Java code.
Here's a picture illustrating how to create and run a program using JLex:
</p>
<img src="diagrams/jlex.png">

<p>
The input to JLex is a specification that includes a set of regular
expressions and associated actions.
The output of JLex is a Java source file that defines a class named
<b>Yylex</b>.
Yylex includes a constructor that is called with one argument:
the input stream (an InputStream or a Reader).
It also includes a method called <b>next_token</b>, which returns the
next token in the input.
<P>
The picture above assumes that a class named P2 has been defined
that contains the core program of interest.
That program will declare an object of type Yylex, and will include
calls to the Yylex constructor and its next_token method.
<P>
<a name=format></a>
<H2>Format of a JLex Specification</H2>
<P>
A JLex specification has three parts, separated by double percent signs:
<OL class=style1>
  <LI> <b>User code</b>: this part of the specification will not be
        discussed here.
  <LI> <b>JLex directives</b>: This includes macro definitions (described
        below).
	See the
	<A HREF="http://www.cs.princeton.edu/~appel/modern/java/JLex">
	JLex Reference Manual</A> for more information about this part
	of the specification.
  <LI> <b>Regular expression rules</b>: These rules specify how to divide
       up the input into tokens.  Each rule includes an optional state list,
       a regular expression, and an associated action.
       
</OL>
We will discuss the regular expression rules part first.

<P>
<a name=rules></a>
<H2>Regular Expressions Rules</H2>
<P>
The state-list part of a rule is discussed <A HREF="#states">below</A>.
Ignoring state-lists for now, the form of a regular expression rule is:
</p>
<div align=center>
<img src="diagrams/jlex_rules.png">
</div>
<P>
When the scanner's next_token method is called, it repeats:
<OL class=style1>
  <LI> Find the longest sequence of characters in the input (starting with
       the current character) that matches a regular-expression pattern.
  <LI> Perform the associated action.
</OL>
<p>
until an action causes the next_token method to return.
If there are several patterns that match the same (longest)
sequence of characters, then the first such pattern is considered
to be matched (so the order of the regular-expression
rules can be important).
</p>
<P>
If an input character is not matched in any pattern, the scanner throws
an exception.
It is <em>not</em> good to have a scanner that can "crash" on bad input,
so it is important to make sure that there can be no such
unmatched characters!
<P>
The regular expressions are similar to the ones discussed in the
scanner notes.
Here's how they are used to match the input:
<UL class="style1" style="list-style-type:disc">
  <LI> Most characters match themselves.  For example:
		<UL class="style1" style="list-style-type:circle">
			<li><pre>abc</pre></li>
			<li><pre>==</pre></li>
			<li><pre>while</pre></li>
		</ul>
       are three patterns that match exactly those sequences of characters
       (note that writing one character after another means "followed by"
       as usual).

  <P>
  <LI> Characters (even special characters, except backslash)
       enclosed in double quotes match
       themselves.  For example, the following patterns are equivalent to the 
       three given above:
			 <ul class="style1" style="list-style-type:circle">
       <li><pre>&quot;abc&quot;</pre></li>
       <li><pre>&quot;==&quot;</pre></li>
       <li><pre>&quot;while&quot;</pre></li>
			 <ul>
       And the following pattern:
       <pre>
       &quot;a|b&quot;
       </pre>
       matches the three-character sequence: <code>a</code> then <tt>|</tt>
       then <tt>b</tt>, rather than matching a single <tt>a</tt> or a
       single <tt>b</tt>.

  <P>
  <LI> The following characters have the usual special meanings as regular
       expression operators:
       <TABLE border>
       <tr><td align=center>|
           <td>means "or"
       <tr><td align=center>*
           <td> means zero or more instances of
       <tr><td align=center>+
           <td> means one or more instances of
       <tr><td align=center>?
           <td> means zero or one instance of
       <tr><td align=center> ( )
           <td> are used for grouping
       </TABLE>

  <P>
  <LI> The dot character matches <em>any</em> character except the newline
       character.
       It is usually used in the last rule in the specification,
       to match all "bad" characters (and the associated action issues
       an error message).

  <P>
  <LI> The backslash is a special <em>escape</em> character:
  <UL>
  <TABLE border>
  <tr><td>\n<td>newline
  <tr><td>\t<td>tab
  <tr><td>\"<td>double quote
  </TABLE>
  </UL>
  To match a backslash character, put <i>two</i> backslashes in a character
  class (see below).
  See the
  <A HREF="http://www.cs.princeton.edu/~appel/modern/java/JLex">
  JLex Reference Manual</A> for a complete list of the special characters
  escaped by a backslash.

  <P>
  <LI> The carat and dollar-sign characters: <tt>^</tt> and <tt>$</tt>,
       are special characters.  When the carat is used as the first
       character in a pattern, it causes the pattern to match only at
       the beginning of a line (i.e., only if the previous character was
       a newline).  When the dollar sign is used as the last character
       in a pattern, it causes the pattern to match only at the end of
       a line (i.e., only if the next character is a newline).

  <P>
  <LI> The regular expression can include <b>character classes</b>,
       delimited by square brackets:
		<UL class="style1" style="list-style-type:circle">
       <LI> A character class will match <em>one</em> character.
       <LI> If no special characters are used inside the character class,
            then the character class matches any of the characters it includes
	    inside its square brackets.
	    For example: <tt>[abc]</tt> matches an <tt>a</tt>, or a
	    <tt>b</tt>, or a <tt>c</tt>, so it is the same as: <tt>a|b|c</tt>.
       <LI> Here are the characters that are "special" inside a character
            class:
	    <TABLE border>
	    <tr><td>-<td>means a range of characters; e.g., <tt>a-z</tt>
	                 means "a to z".
            <tr><td>^<td>is only a special character if it is the
	                 <em>first</em> character in the square brackets;
			 it means <em>not</em> any of the following
			 characters.  So for example, <tt>[^abc]</tt>
			 matches any character other than an <tt>a</tt>, or a
			 <tt>b</tt>, or a <tt>c</tt>.

            <tr><td>\<td>is used as an escape character with <tt>n</tt>,
	                 <tt>b</tt>, <tt>"</tt>, etc as usual;
	                 it can also be used to escape the characters that
			 are special inside a character class (e.g.,
			 <tt>[a\-z]</tt> matches an <tt>a</tt> or
			 a <tt>-</tt> or a <tt>z</tt>, and <tt>[\\]</tt>
			 matches a backslash.
            <tr><td>"<td>can be used around characters that are special inside
	                 a character class to make them match themselves (e.g.,
			 <tt>["\"]</tt> matches a backslash, and
			 <tt>["-"]</tt> matches a hyphen.  To include a
			 double-quote character in a character class,
			 escape it with a backslash.
	    </TABLE>
     </UL>
</UL>
<P>
Note that whitespace only matches itself if it is inside quotes or in
a character class;
otherwise, it <em>ends</em> the current pattern.
So the two rules:
<UL>
<pre>
[a bc]    {}
a|&quot; &quot;|b|c {}
</pre>
</UL>
are equivalent;
each matches an <tt>a</tt>, or a space, or a <tt>b</tt>, or a <tt>c</tt>,
while the rule:
<UL>
<pre>
a bc   {}
</pre>
</UL>
causes an error when you try to process your specification.
<hr>
<a name=youtry1>
<P>
<center>
<u><b>TEST YOURSELF #1</b></u>
</center></a>
<P>
<b>Question 1:</b>
The character class <tt>[a-zA-Z]</tt> matches any letter.
Write a character class that matches any letter or any digit.
<P>
<b>Question 2:</b>
Write a pattern that matches any Pascal identifier (a sequence of
one or more letters and/or digits, starting with a letter).
<P>
<b>Question 3:</b>
Write a pattern that matches any C identifier (a sequence of
one or more letters and/or digits and/or underscores, starting with
a letter or underscore).
<P>
<b>Question 4:</b>
Write a pattern that matches any C identifier that does not
<em>end</em> with an underscore.
<p><a href="SOLUTIONS/JLEX-ANSWERS.html#ans1">solution</a></p>
<hr>

<P>
<a name=dirs></a>
<H2>JLex directives</H2>
<P>
Recall that the second part of a JLex specification contains directives.
This can include specifying the value that should be returned on end-of-file,
specifying that line counting should be turned on, and specifying that
the scanner will be used with the Java parser generator java cup.
(See the
<A HREF="manual.html">
JLex Reference Manual</A> for more information about directives.)
<P>
The directives part also includes <b>macro definitions</b>.
The form of a macro definition is:
<UL>
<pre>
name = regular-expression
</pre>
</UL>
where <tt>name</tt> is any valid Java identifier, and
<tt>regular-expression</tt> is any regular expression as defined above.
Here are some examples:
<UL>
<pre>
DIGIT =		[0-9]
LETTER =	[a-zA-Z]
WHITESPACE =	[ \t\n]
</pre>
</UL>
<P>
Once a macro has been defined, it can be used in a regular expression
(either to define another macro, or in the "Regular Expression Rules"
part of the JLex specification.
To use a macro, just use its name inside curly braces.
For example, given the above macro definitions, the following pattern
could be used to match Pascal identifiers:
<UL>
<pre>
{LETTER}({LETTER}|{DIGIT})*
</pre>
</UL>
<hr>
<a name=youtry2>
<P>
<center>
<u><b>TEST YOURSELF #2</b></u>
</center></a>
<P>
Define a macro named NOTSPECIAL that matches any character except a
newline, double quote, or backslash.
<p><a href="SOLUTIONS/JLEX-ANSWERS.html#ans2">solution</a></p>
<hr>

<P>
<a name=comments></a>
<H2>Comments </H2>
<P>
You can include comments in the first and second parts of your JLex
specification, but not in the third part (because JLex will think
they are part of a pattern).
JLex comments are like Java comments: they start with two slashes,
and continue to the end of the line.

<P>
<a name=states></a>
<H2>States </H2>
<P>
Recall that each regular expression rule (a pattern and the action
to be performed when the pattern is matched) can optionally include
a <em>list of states</em> at the beginning of the pattern.
For example:
<UL><tt>&lt;STATE1, STATE2&gt;&quot;abc&quot; { }
    </tt>
</UL>
is a rule that starts with a list of two states (named <tt>STATE1</tt>
and <tt>STATE2</tt>).
<P>
Each time the scanner is called, it is in some state.
Initially, it is in a special state called <tt>YYINITIAL</tt>.
It will stay in that state unless it matches a pattern whose
corresponding action includes code that causes
it to change to another state.
For example, given the rule:
<UL><tt>&quot;xyz&quot;  { yybegin(STATE1); }
    </tt>
</UL>
if the input contains &quot;xyz&quot;, then the call to <tt>yybegin</tt>
will be executed, and the scanner will enter the <tt>STATE1</tt> state.
<P>
If a rule has <em>no</em> list of states, then it will be matched
in any state;
however, if it has a list of states, then it will be matched only
when the scanner is in one of those states.
So for example, the rule for &quot;abc&quot; given above will only
be matched after the rule for &quot;xyz&quot; has been matched.
<P>
Every state other than <tt>YYINITIAL</tt> must be declared in the
JLex directives part of the JLex specification.
Here's an example declaration:
<UL><tt>%state STATE1
    </tt>
</UL>

<P>
Suppose that for floating-point numbers you want your scanner to return
two values: the value before the decimal point, and the value after the
decimal point.
Here's an example of using a JLex state to do that (using some
pseudo-code):
<UL>
  <pre>
  %%

  DIGIT= [0-9]
  DOT=   "."

  %state DOTSTATE

  %%

  &lt;YYINITIAL&gt;{DIGIT}+{DOT}    { yybegin(DOTSTATE);
                                -- save the value so far --
                              }

  &lt;DOTSTATE&gt;{DIGIT}+          { yybegin(YYINITIAL);
                                -- return the saved value and the new one --
                              }
  </pre>
</UL>
<hr>
<a name=youtry3>
<P>
<center>
<u><b>TEST YOURSELF #3</b></u>
</center></a>
<P>
A <em>quoted string</em> consists of three parts:
  <OL>
    <LI> A double quote.
    <LI> Some text.
    <LI> A double quote.
  </OL>
The text can contain any characters except a newline or a single
double-quote character.
It <em>can</em> contain an &quot;escaped&quot; quote, which is
two double-quote characters in a row.
<P>
Use JLex states to write a specification to recognize quoted strings,
and to return the number of escaped quotes in each such string.
To declare a counter, declare a class with a static, public int field,
in the "User Code" part of the JLex specification, and
update/return that static field.
<p><a href="SOLUTIONS/JLEX-ANSWERS.html#ans3">solution</a></p>
<hr>
<a name=vars></a>
<H2>yyline and yytext</H2>
<P>
If you turn line counting on (by including <tt>%line</tt> in the
"directives" part of the specification),
you can use the variable <tt>yyline</tt> in the actions that
you write for the regular expressions.
The value of <tt>yyline</tt> will be the current line number in the
input file, counting from zero (so to use that number in error messages
printed by your scanner, you will need to add one to <tt>yyline</tt>).
<P>
You can also use the method <tt>yytext()</tt> in your actions.
This method returns a String -- the sequence of characters that
was just matched.

<P>
<a name=example></a>
<H1>A Small Example</H1>
<P>
Here is a small (complete) JLex specification:
<pre>
%%

DIGIT=		[0-9]
LETTER=		[a-zA-Z]
WHITESPACE=	[ \t\n]      // space, tab, newline

// The next 3 lines are included so that we can use the generated scanner
// with java CUP (the Java parser generator)
%implements java_cup.runtime.Scanner
%function next_token
%type java_cup.runtime.Symbol

// Turn on line counting
%line

%%

{LETTER}({LETTER}|{DIGIT})* {System.out.println(yyline+1 + &quot;: ID &quot; + yytext());}
{DIGIT}+                    {System.out.println(yyline+1 + &quot;: INT&quot;);}
&quot;=&quot;                         {System.out.println(yyline+1 + &quot;: ASSIGN&quot;);}
&quot;==&quot;                        {System.out.println(yyline+1 + &quot;: EQUALS&quot;);}
{WHITESPACE}*               { }
.               	    {System.out.println(yyline+1 + &quot;: bad char&quot;);}
</pre>

<P>
Note that the actions in this example are <em>not</em> what you would
really put in a JLex specification for a scanner.
Instead of printing, the first four actions should return the
appropriate tokens.
<P>
<a name=quick></a>
<H2>Quick Reference Guide</H2>
<P>
<b>Operators and Special Symbols in JLex</b>
<P>
The following table summarizes the operators and special symbols used in JLex.
Note that some characters have an entirely different meaning when used
in a regular expression and in a character class.
Character classes are always delimited by square brackets;
they can be used in the regular expressions that define macros, as well as in
the regular expressions used to specify a pattern to be matched in the input.
<P>
<TABLE border>
<tr><td><b>Symbol</b>
    <td align=center><b>Meaning in Regular Expressions</b>
    <td><b>Meaning in Character Classes</b>
<tr><td align=center>(
    <td> Matches with ) to group sub-expressions.
    <td> Represents itself.
<tr><td align=center>)
    <td> Matches with ( to group sub-expressions.
    <td> Represents itself.
<tr><td align=center>[
    <td> Begins a character class.
    <td> Represents itself.
<tr><td align=center>]
    <td> Is illegal.
    <td> Ends a character class.
<tr><td align=center>{
    <td> Matches with } to delimit a macro name.
    <td> Matches with } to delimit a macro name.
<tr><td align=center>}
    <td> Matches with { to delimit a macro name.
    <td> Represents itself or matches with { to delimit a macro name.
<tr><td align=center>"
    <td> Matches with " to delimit strings (only \ is special within strings).
    <td> Matches with " to delimit a string of characters that belong to the character
         class.&nbsp; Only \&quot; is special within the string.
<tr><td align=center>\
    <td> Escapes special characters (n, t, etc).
	 Also used to specify a character by its octal, hexadecimal, or
	 unicode value.
    <td> Escapes characters that are special inside a character class.
<tr><td align=center>.
    <td> Matches any one character except newline.
    <td> Represents itself.
<tr><td align=center>|
    <td> Alternation (or) operator.
    <td> Represents itself.
<tr><td align=center>*
    <td> Kleene closure operator (zero or more matches).
    <td> Represents itself.
<tr><td align=center>+
    <td> Positive closure operator (one or more matches).
    <td> Represents itself.
<tr><td align=center>?
    <td> Optional choice operator (zero or one matches).
    <td> Represents itself.
<tr><td align=center>^
    <td> Matches only at beginning of a line.
    <td> When it is the first character in the character class,
         complements the remaining characters in the class.
<tr><td align=center>$
    <td> Matches only at end of a line.
    <td> Represents itself.
<tr><td align=center>-
    <td> Represents itself.
    <td> Range of characters operator.
</TABLE>
</body>
</html>
